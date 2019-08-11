<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Simple Picture Store</title>
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="/static/bootstrap.min.css">
    <script src="/static/jquery-3.4.0.js"></script>
    
    <style type="text/css">
        .centerEle {
            height: 300px;
            display: -webkit-flex;
            display: flex;
            -webkit-align-items: center;
            align-items: center;
            -webkit-justify-content: center;
            justify-content: center;
        }
    </style>

    <script type="text/javascript">
        function fileLoad(ele){
            var formData = new FormData();
            var files = $(ele)[0].files[0];
            formData.append("picture", files);
            $.ajax({
                url: "upload.php",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (responseStr) {
                    my_container = document.getElementById("my_container");
                    console.log(responseStr);
                    if (responseStr.success){
                        var img_origin = document.createElement("img");
                        img_origin.src = responseStr.success.orgin;
                        var img_sample = document.createElement("img");
                        img_sample.src = responseStr.success.sample;
                        my_container.append(img_origin);
                        my_container.append(img_sample);
                    }else{
                        error_message = document.createElement("p");
                        error_message.innerHTML = responseStr.error;
                        my_container.append(error_message);
                    }
                }
                ,
                error : function (responseStr) {
                    alert("出错啦");
                }
            });
        }

        $(function () {
            var $input =  $("#upload");
            $input.change(function () {
                if($(this).val() != ""){
                    fileLoad(this);
                }
            })
        })
    </script>
    
</head>
<body>
<div class="centerEle">
    <div class="form-group col-md-3  mb-2">
        <input type="text" name="viewfile" id="viewfile" placeholder="未选择文件" disabled autocomplete="off" class="form-control">
        <input type="file" style="display: none" multiple="multiple" id="upload"/>
    </div>
    <label class="btn btn-primary  mb-2" for="upload">浏览</label>
</div>
<div id="my_container" class="container">
</div>
</body>
</html>