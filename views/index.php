<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Protest</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="/protest/extentions/chosen/chosen.min.css">
</head>
<body>
<div class="container">
    <form method="post" id="form">
        <div class="alert"></div>
        <div class="form-group">
            <label for="FIO">ФИО</label>
            <input name="name" type="text" class="form-control" id="FIO" aria-describedby="fioHelp" placeholder="Введите ФИО" >
        </div>
        <div class="form-group">
            <label for="InputEmail">Email address</label>
            <input name="email" type="email" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Введите email">
        </div>
        <div class="form-group region">
            <label for="region">Область</label>
            <select name="region" class="form-control" id="region" data-placeholder="Выберите область">
                <option></option>
                <?php
                foreach ($data as $value){
                    foreach($value as $key => $val){
                        echo "<option value='$key'>$val</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group district" style="display:none;">
            <label for="district">Район</label>
            <select name="district" class="form-control" id="district" data-placeholder="Выберите район">
            </select>
        </div>
        <div class="form-group city" style="display:none;">
            <label for="city">Город</label>
            <select name="city" class="form-control" id="city" data-placeholder="Выберите город">
            </select>
        </div>
        <input id="submit" name="submit" type="submit" class="btn btn-primary" value="Отправить">
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="/protest/extentions/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#region').chosen();
        $('.district').hide();
        $('.city').hide();

        function selectAddress (data){
            var items = [];
            items.push('<option></option>');
            $.each(data, function(i, v){
                $.each(v, function(key, val) {
                    items.push('<option value="' + key + '">' + val + '</option>');
                });
            });
            return items;
        }
        $("#region").change(function() {
            var region = $('#region').val();
            $(".alert-region").remove();
            $('#district').html('');
            $('#city').html('');
            $('.city').hide();
            $('#city').trigger('chosen:updated');
            $('#district').trigger('chosen:updated');
            $.ajax({
                dataType: 'json',
                url: 'index/address',
                type: "POST",
                data: {'region': region},
                success: function (data) {
                    if(data == null){
                        $('.district').hide();
                    } else {
                        var items = selectAddress(data);
                        $('#district').append().html(items.join(''));
                        $('.district').show();
                        $('#district').chosen();
                        $('#district').trigger('chosen:updated');
                    }
                }
            });
        });
        $("#district").change(function() {
            var district = $('#district').val();
            $('#city').html('');
            $('#city').trigger('chosen:updated');
            $.ajax({
                dataType: 'json',
                url: 'index/address',
                type: "POST",
                data: {'district': district},
                success: function (data) {
                    if(data == null){
                        $('.city').hide();
                    } else {
                        var items = selectAddress(data);
                        $('#city').append().html(items.join(''));
                        $('.city').show();
                        $('#city').chosen();
                        $('#city').trigger('chosen:updated');
                    }
                }
            });
        });

        $('#form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 5,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                name: {
                    required: "Пожалуйста введите ФИО",
                    minlength: "Количество символов не должно быть меньше 5",
                    maxlength: "Количество символов не должно быть больше 100"
                },
                email: {
                    required: "Пожалуйста введите email",
                    email: "Введите валидный почтовый ящик"
                }
            }
        });
        $('#submit').on('click', function (e) {
            e.preventDefault();
            $('.alert').text('');
            $('.alert').removeClass("alert-danger alert-success");
            $(".alert-region").remove();
            var form_data = $('#form').serialize();
            if ($("#region").val() != '') {
                if ($('#form').valid()) {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: 'index/save',
                        data: form_data,
                        success: function (data) {
                            if (typeof(data.name) != 'undefined' && data.error != "region") {
                                $('.alert').addClass("alert-success").text("Вы успешно сохранили данные " + data.name + "!");
                                $('#form').trigger('reset');
                                $("#district").html('');
                                $("#city").html('');
                                $("#region").trigger('chosen:updated');
                                $("#district").trigger('chosen:updated');
                                $("#city").trigger('chosen:updated');
                                $('.district').hide();
                                $('.city').hide();
                            } else {
                                $('.alert').addClass("alert-danger").text('Карточка с таким же email: ' + data.email + ' уже существует!');
                            }
                        },
                        error: function (xhr, str) {
                            alert('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
                }
            } else {
                $('.region').append("<span class='alert-region'>Вы не выбрали регион</span>")
            }
        });
    });
</script>
</body>
</html>
