<!doctype html>
<html>
    <head>
        <title>datepicker</title>
    </head>
<link href="../lummenu/css/bootstrap.min.css" rel="stylesheet">
<link href="../lummenu/css/datepicker3.css" rel="stylesheet">
<link href="../lummenu/css/styles.css" rel="stylesheet">

<link href="../lummenu/css/adminstyle.css" rel="stylesheet">
<link href='../css/tombol.css' rel='stylesheet' type='text/css'>

<script src="../lummenu/js/bootstrap.js"></script>

<script src="../lummenu/js/lumino.glyphs.js"></script>
<script src="../lummenu/js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>

        <script src="../lummenu/js/jquery-2.2.3.min.js"></script>
        <script src="../lummenu/js/bootstrap.js"></script>
        <script src="../lummenu/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#tanggal').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose:true
                });
            });
        </script>

    <style>
        body{padding: 20px}
    </style>
    <body>

        <form action="action">
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="text" name="tanggal" id="tanggal" />
            </div>
        </form>
    </body>
</html>
