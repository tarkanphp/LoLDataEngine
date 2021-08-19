<html>
    <head>
        <meta charset="utf-8">
    <title> LoLDataEngine </title>
</head>
<body>
<div id="data">
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    $(document).ready(function () 
{
    function Refresh()
    {

        gameEngine_v2();
        setTimeout(function()
        {
            
        },1000);
        
        summonerEngine_v2();
        setTimeout(function()
        {
            
        },1500);
        
        gameDiscoverEngine_v2();
        setTimeout(function()
        {
            
        },2000);
            
       
    }
        function gameEngine_v2()
            {
                $.ajax({
                            method: "GET",
                            url: "gameEngine_v2.php",
                            success: function (response) 
                            {
                                console.log(response);
                                $("#data").load(response);
                            }
                        });
            }
        function summonerEngine_v2()
        {
                $.ajax({
                            method: "GET",
                            url: "summonerEngine_v2.php",
                            success: function (response) 
                            {
                                console.log(response);
                                $("#data").load(response);
                            }
                        });
        }
        function gameDiscoverEngine_v2()
        {
                $.ajax({
                            method: "GET",
                            url: "gameDiscoverEngine_v2.php",
                            success: function (response) {
                                console.log(response);
                                $("#data").load(response);
                            }
                        });
        }

        setInterval(function()
            {
                        Refresh();
            },10000);
});
    </script>
</body>
</html>