<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB Connection</title>
</head>
<body>
    <div>
        <?php
            use Illuminate\Support\Facades\DB;
            if(DB::connection()->getPdo()){
                echo "Successfully connected to the database named " . DB::connection()->getDatabaseName();

            }
        ?>
    </div>
</body>
</html>