<html>
    <head>
        <title>socket test</title>
        <script src="jquery-3.3.1/jquery-3.3.1.min.js"></script>
    <style type="text/css">
    .socket_center{
        margin:0 auto;
        width: 100px;
        height: 100x;
    }
    </style>
    </head>
    <body>
        <br>
        <br>
        <div class="socket_center">
            <input type="text" id="content">
            <input type="button" value="send" id="send">
            <div id="message_info"></div>
        </div>
    
        <script type="text/javascript">
            var ws = new WebSocket("ws://118.24.16.28:4000");
            ws.onopen = function(){
                console.log("握手成功");
            }
            ws.onmessage = function(e){
                console.log("message:" + e.data);
                $("#message_info").append('<div>'+e.data+'</div><br>');
            }
            ws.onerror = function(){
                console.log("error");
            }
            $("#send").click(function(){
                content = $("#content").val();
                console.log(content);
                ws.send(content);
            })
        </script>
    </body>
</html>