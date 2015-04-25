<?php
require_once "config.php";
$object = new BulletScreen();

$pwd = array(
	"Rijn_10011",
	"xmtsd1860",
	"RmTZx",
);

?>

<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Manager - BBulletScreenS</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1>Manager <small>BulletScreen</small></h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">权限</div>
                        <div class="panel-body">
                            <form class="form-signin" action="" method="post">
                                <label for="inputPassword" class="sr-only">Password</label>
                                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required autofocus>
                                <br/>
                                <button class="btn btn-default btn-block" name="submit-login" type="submit">登录</button>
                            </form>
                            <br/>
<?php
if (isset($_POST['submit-login']))
{
	if (in_array($_POST['password'], $pwd))
	{
		$id = $_POST['password'];
		echo ('<div class="alert alert-success" role="alert">id = '.substr_replace($id, "****", -4).'</div>');
		$_SESSION['id'] = $_POST['password'];
	}
	else
	{
		echo ('<div class="alert alert-danger" role="alert">Login failed.</div>');
	}
}
else
{
	if (isset($_SESSION['id']) && in_array($_SESSION['id'], $pwd))
	{
		$id = $_SESSION['id'];
		echo ('<div class="alert alert-success" role="alert">id = '.substr_replace($id, "****", -4).'</div>');
	}
}
if ( ! isset($id))
{
	exit;
}

# Process
if (isset($_POST['display-service']))
{
	if ($object->mem->get("BulletScreen-Display") == false)
	{
		$status = $object->mem->set("BulletScreen-Display", "1");
	}
	else
	{
		$status = $object->mem->delete("BulletScreen-Display");
	}
}
if (isset($_POST['fetch-service']))
{
	if ($object->mem->get("BulletScreen-Fetch") == false)
	{
		$status = $object->mem->set("BulletScreen-Fetch", "1");
	}
	else
	{
		$status = $object->mem->delete("BulletScreen-Fetch");
	}
}
if (isset($_POST['auto-push']))
{
	if ($object->mem->get("BulletScreen-Auto") == false)
	{
		$status = $object->mem->set("BulletScreen-Auto", "1");
	}
	else
	{
		$status = $object->mem->delete("BulletScreen-Auto");
	}
}

if (isset($_POST['delete-item']))
{
	$id = @$_POST['delete-item'];
	if ($id)
	{
		$query = $object->remove_mysql(
			'bulletscreen',
			"`id` = $id"
		);
	}
}
if (isset($_POST['delete-all']))
{
	$query = $object->remove_mysql(
		'bulletscreen',
		"1 = 1"
	);
}
if (isset($_POST['push-item']))
{
	$id = @$_POST['push-item'];
	if ($id)
	{
		$query = $object->modify_mysql(
			'bulletscreen',
			'`status` = 1',
			"`id` = $id"
		);
	}
}
if (isset($_POST['push-all']))
{
	$query = $object->modify_mysql(
		'bulletscreen',
		'`status` = 1',
		"1=1"
	);
}
if (isset($_POST['top-item']))
{
	$id = @$_POST['top-item'];
	if ($id)
	{
		$query = $object->modify_mysql(
			'bulletscreen',
			'`status` = 2',
			"`id` = $id"
		);
	}
}
if (isset($_POST['emphasis-all']))
{
	$query = $object->modify_mysql(
		'bulletscreen',
		'`status` = 2',
		"1=1"
	);
}
if (isset($_POST['pull-item']))
{
	$id = @$_POST['pull-item'];
	if ($id)
	{
		$query = $object->modify_mysql(
			'bulletscreen',
			'`status` = 0',
			"`id` = $id"
		);
	}
}
if (isset($_POST['pull-all']))
{
	$query = $object->modify_mysql(
		'bulletscreen',
		'`status` = 0',
		"1=1"
	);
}

$fetch_status   = $object->mem->get("BulletScreen-Fetch")   == false ? 'btn-default' : 'btn-success';
$display_status = $object->mem->get("BulletScreen-Display") == false ? 'btn-default' : 'btn-success';
$auto_status    = $object->mem->get("BulletScreen-Auto")    == false ? 'btn-default' : 'btn-success';
?>
                        </div>
                    </div>
<form action="" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">Service</div>
                        <div class="panel-body">
                            <p><div class="btn-group" role="group" aria-label="...">
                                <input type="submit" name="fetch-service" class="btn btn-default <?=$fetch_status?>" value="Fetch">
                            </div></p>
                            <p><div class="btn-group" role="group" aria-label="...">
                                <input type="submit" name="display-service" class="btn btn-default <?=$display_status?>" value="Display">
                            </div></p>
                            <p><div class="btn-group" role="group" aria-label="...">
                                <input type="submit" name="auto-push" class="btn <?=$auto_status?>" value="AutoPush">
                            </div></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
<div class="btn-toolbar" role="toolbar" aria-label="...">
    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="refresh" class="btn btn-default" value="Refresh">
    </div>
    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="delete-all" class="btn btn-danger" value="Delete">
        <input type="submit" name="pull-all" class="btn btn-warning" value="Pull">
        <input type="submit" name="push-all" class="btn btn-success" value="Push">
        <input type="submit" name="emphasis-all" class="btn btn-info" value="Emphasis">
    </div>
</div>

    <table class="table table-hover">
      <thead>
        <tr>

          <th>Actions</th>
          <th>Nickname</th>
          <th>Time</th>
          <th>Content</th>
        </tr>
      </thead>
      <tbody>
<?php
$query = $object->get_mysql(
	'bulletscreen',
	'*',
	'',
	'`status` DESC, `time` DESC',
	false
);
while ($data = mysql_fetch_array($query))
{

	?>
        <tr
<?php
switch ($data[4])
	{
		case 1:
			echo ('class="success"');
			break;
		case 2:
			echo ('class="info"');
			break;
	}
	?>
        >

            <td>
                <div class="btn-group" role="group" aria-label="...">
                    <input type="submit" class="btn btn-xs btn-danger" value="<?=$data[0]?>" name="delete-item"/>
                    <input type="submit" class="btn btn-xs btn-warning" value="<?=$data[0]?>" name="pull-item"/>
                    <input type="submit" class="btn btn-xs btn-success" value="<?=$data[0]?>" name="push-item"/>
                    <input type="submit" class="btn btn-xs btn-info" value="<?=$data[0]?>" name="top-item"/>
                </div>
            </td>
            <td><?=$data[1]?></td>
            <td><?=$data[2]?></td>
            <td><?=$data[3]?></td>
        </tr>
        <?php
}
?>
      </tbody>
    </table>
                </div>
            </div>
        </div>
</form>

    <footer>
        <hr/>
        <div class="container">
            <p>&copy; Rijn, pixelnfinite.com, 2015.</p>
        </div>
    </footer>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>