<meta charset=utf-8>
<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
</head>

<body>
    <div id='container'>
    <div id='mainpic'>
    </div>

    <div id='menu'>
        <?php
            //User logged in
            if (Session::has('userRole')) {
                $value = Session::get('userRole');
                // User Log In
                if($value==0){ 
        ?>
                <p1></p1>
                <a href="<?php echo url('home') ?>">Home</a>
                <p1></p1>
                <p1></p1>
                <p1></p1>
                <p1></p1>
                <p1></p1>
                <a href ="logout" style="color:red">Log Out</a>
        <?php
                }
                // If 
                elseif($value==1){ 
        ?>
                    <p1></p1>
                    <a href="<?php echo url('home') ?>">Home</a>
                    <a href="<?php echo url('stocks') ?>">Stock Level</a>
                    <p1></p1>
                    <p1></p1>
                    <p1></p1>
                    <p1></p1>
                    <a href ="logout" style="color:red">Log Out</a>
        <?php
                }
            }
            
            //User not logged in
            else{
        ?>  
                <p1></p1>
                <a href="<?php echo url('home') ?>">Home</a>
                <p1></p1>
                <p1></p1>
                <p1></p1>
                <p1></p1>
                <p1></p1>
                <p1></p1>
                <p1></p1>          
                <a href ="login" style="color:#66FF00">Log In</a>
        <?php    
            }
        ?>
        
    </div>
</body>