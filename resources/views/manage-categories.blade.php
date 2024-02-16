
<html>
<head>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script>
        var __Data = {
            user: Object.assign({!! Auth::user() !!}, { admin : {!! Auth::user()->can('manage') !!} ,  superadmin: {!! Auth::user()->can('create_admins') ? 1 : 0 !!} }),
        }

    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    
    



    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/favicon-128.png" sizes="128x128">
    <meta name="application-name" content="Pdfglue">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="msapplication-square70x70logo" content="/mstile-70x70.png">
    <meta name="msapplication-square150x150logo" content="/mstile-150x150.png">
    <meta name="msapplication-wide310x150logo" content="/mstile-310x150.png">
    <meta name="msapplication-square310x310logo" content="/mstile-310x310.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="xoa5nsRNQIURWaTVaXV5xROZ7CHCLuJoTCOoMLm9">

    <title>PdfGlue</title>
    <!-- Styles -->
    <link href="/css/app.css?id=3a35592ef42ce10786e5" rel="stylesheet">

<style>
element {

}
.rowcheck,#example-select-all{
      -webkit-appearance: checkbox;
    -moz-appearance: checkbox;
}
#app {

    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    height: 100vh;

}
* {

    -webkit-box-sizing: border-box;
    box-sizing: border-box;
     outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;

}

body {

    font-family: Raleway,sans-serif;
    font-size: 14px;

}

.wrapper[data-v-147e06e2] {

    z-index: 2;

}



.wrapper .container[data-v-147e06e2] {

    width: 100%;
    z-index: 1;
    background: #fff;
    height: 70px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-direction: row;
    flex-direction: row;
    border-bottom: 1px solid #d4d5d6;
    -webkit-box-shadow: 0 3px 5px -1px rgba(0,0,0,.04),0 4px 8px rgba(0,0,0,.03),0 1px 16px rgba(0,0,0,.02);
    box-shadow: 0 3px 5px -1px rgba(0,0,0,.04),0 4px 8px rgba(0,0,0,.03),0 1px 16px rgba(0,0,0,.02);

}

.wrapper .container .logo.admin[data-v-147e06e2] {

    background: none;

}
.wrapper .container .logo[data-v-147e06e2] {

    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    min-width: 350px;
    cursor: pointer;
    background: #c39000;

}

.wrapper .container .logo img[data-v-147e06e2] {

    width: 110px;

}

.wrapper .container .logo[data-v-147e06e2] {

    cursor: pointer;

}



@media (max-width:1260px) {
 .wrapper .container[data-v-147e06e2] {
  width:100%
 }
}
@media screen and (max-width:1024px) {
 .wrapper .container[data-v-147e06e2] {
  height:50px
 }
}


.wrapper .container .logo.admin .text[data-v-147e06e2] {
 color:#c39000
}
.wrapper .container .logo .text[data-v-147e06e2] {
 text-align:center;
 color:#fff;
 font-size:26px
}
.wrapper .container .logo .text .start[data-v-147e06e2] {
 text-transform:uppercase;
 font-weight:800
}
.wrapper .container .logo .text .end[data-v-147e06e2] {
 text-transform:capitalize;
 font-weight:600
}

@media (max-width:1024px) and (min-width:880px) {
 .wrapper .container .logo[data-v-147e06e2] {
  display:none
 }
}
@media (max-width:1024px) {
 .wrapper .container .logo[data-v-147e06e2] {
  margin-left:-78px
 }
}
@media (max-width:738px) {
 .wrapper .container .logo[data-v-147e06e2] {
  margin-left:-112px
 }
 .wrapper .container .logo img[data-v-147e06e2] {
  width:90px
 }
}
.wrapper .container .links[data-v-147e06e2] {
 display:-webkit-box;
 display:-ms-flexbox;
 display:flex;
 -webkit-box-flex:1;
 -ms-flex-positive:1;
 flex-grow:1;
 padding-left:50px
}
.wrapper .container .links .link[data-v-147e06e2] {
 text-decoration:none;
 display:-webkit-box;
 display:-ms-flexbox;
 display:flex;
 -webkit-box-pack:center;
 -ms-flex-pack:center;
 justify-content:center;
 padding-left:40px;
 padding-right:40px;
 color:#666;
 font-weight:600;
 font-size:16px;
 position:relative;
 cursor:pointer;
 -webkit-transition:color,.15s,ease-in-out;
 -o-transition:color,.15s,ease-in-out;
 transition:color,.15s,ease-in-out
}
.wrapper .container .links .link.small .text[data-v-147e06e2] {
 margin-left:-5px;
 margin-right:-5px
}
.wrapper .container .links .link.small .icon[data-v-147e06e2] {
 display:none
}
.wrapper .container .links .link .icon[data-v-147e06e2] {
 width:27px;
 font-size:22px
}
.wrapper .container .links .link .icon[data-v-147e06e2],.wrapper .container .links .link .text[data-v-147e06e2] {
 display:-webkit-box;
 display:-ms-flexbox;
 display:flex;
 -webkit-box-pack:center;
 -ms-flex-pack:center;
 justify-content:center;
 -webkit-box-orient:vertical;
 -webkit-box-direction:normal;
 -ms-flex-direction:column;
 flex-direction:column;
 text-align:left
}
.wrapper .container .links .link[data-v-147e06e2]:hover {
 color:#c39000
}
.wrapper .container .links .link[data-v-147e06e2]:hover:after {
 width:calc(100% - 80px)
}
.wrapper .container .links .link[data-v-147e06e2]:after {
 content:"";
 height:2px;
 width:0;
 left:40px;
 position:absolute;
 background:#c39000;
 top:70%;
 transform:translateY(-50%);
 -webkit-transition:width,.15s,ease-in-out;
 -o-transition:width,.15s,ease-in-out;
 transition:width,.15s,ease-in-out
}
.wrapper .container .links.admin[data-v-147e06e2] {
 padding-left:0
}
.wrapper .container .links.admin .link[data-v-147e06e2] {
 padding-left:3.3%;
 padding-right:3.3%;
 border-left:1px solid #d4d5d6
}
.wrapper .container .links.admin .link[data-v-147e06e2]:last-child {
 border-right:1px solid #d4d5d6
}
.wrapper .container .links.admin .link[data-v-147e06e2]:after {
 content:"";
 position:absolute;
 left:50%;
 height:2px;
 width:0;
 top:auto;
 bottom:0;
 transform:none;
 background:#c39000;
 -webkit-transition:all,.15s,ease-in-out;
 -o-transition:all,.15s,ease-in-out;
 transition:all,.15s,ease-in-out
}
.wrapper .container .links.admin .link.router-link-active[data-v-147e06e2] {
 color:#c39000
}
.wrapper .container .links.admin .link.router-link-active[data-v-147e06e2]:after {
 left:0;
 width:100%
}
@media (max-width:879px) {
 .wrapper .container .links[data-v-147e06e2] {
  display:none
 }
}
.wrapper .container .user[data-v-147e06e2] {
 -webkit-box-pack:end;
 -ms-flex-pack:end;
 justify-content:flex-end;
 min-width:360px;
 padding-right:40px
}
.wrapper .container .user .account[data-v-147e06e2],.wrapper .container .user[data-v-147e06e2] {
 display:-webkit-box;
 display:-ms-flexbox;
 display:flex;
 -webkit-box-orient:horizontal;
 -webkit-box-direction:normal;
 -ms-flex-direction:row;
 flex-direction:row
}
.wrapper .container .user .account[data-v-147e06e2] {
 -webkit-box-pack:center;
 -ms-flex-pack:center;
 justify-content:center;
 font-weight:600;
 font-size:18px;
 color:#383838;
 background:#fff;
 cursor:pointer
}
.wrapper .container .user .account.active[data-v-147e06e2],.wrapper .container .user .account[data-v-147e06e2]:hover {
 color:#c39000
}
.wrapper .container .user .account .text[data-v-147e06e2] {
 margin-right:10px
}
.wrapper .container .user .account .icon[data-v-147e06e2],.wrapper .container .user .account .text[data-v-147e06e2] {
 display:-webkit-box;
 display:-ms-flexbox;
 display:flex;
 -webkit-box-flex:1;
 -ms-flex-positive:1;
 flex-grow:1;
 -webkit-box-pack:center;
 -ms-flex-pack:center;
 justify-content:center;
 -webkit-box-orient:vertical;
 -webkit-box-direction:normal;
 -ms-flex-direction:column;
 flex-direction:column
}
.wrapper .container .user .account .icon[data-v-147e06e2] {
 padding-top:3px
}
@media (max-width:1024px) {
 .wrapper .container .user[data-v-147e06e2] {
  min-width:auto
 }
}
@media (max-width:879px) {
 .wrapper .container .user[data-v-147e06e2] {
  display:none
 }
}
.wrapper .container .navbar-menu[data-v-147e06e2] {
 z-index:2;
 display:none;
 box-shadow:none;
 font-size:24px;
 position:relative;
 margin-right:43px;
 -webkit-box-flex:1;
 -ms-flex-positive:1;
 flex-grow:1;
 -webkit-box-orient:horizontal;
 -webkit-box-direction:normal;
 -ms-flex-direction:row;
 flex-direction:row;
 -webkit-box-pack:end;
 -ms-flex-pack:end;
 justify-content:flex-end
}
.wrapper .container .navbar-menu .wrapper[data-v-147e06e2] {
 display:-webkit-box;
 display:-ms-flexbox;
 display:flex;
 -webkit-box-orient:vertical;
 -webkit-box-direction:normal;
 -ms-flex-direction:column;
 flex-direction:column;
 -webkit-box-pack:center;
 -ms-flex-pack:center;
 justify-content:center;
 cursor:pointer;
 color:#c39000
}
.wrapper .container .navbar-menu .wrapper.active[data-v-147e06e2],.wrapper .container .navbar-menu .wrapper[data-v-147e06e2]:hover {
 color:#c39000
}
.wrapper .container .navbar-menu .menu-dropdown[data-v-147e06e2] {
 pointer-events:none;
 opacity:0;
 z-index:0;
 position:absolute;
 top:70px;
 width:100vw;
 right:-43px;
 -webkit-box-shadow:0 3px 5px -1px rgba(0,0,0,.22),0 6px 10px rgba(0,0,0,.08),0 1px 18px rgba(0,0,0,.06);
 box-shadow:0 3px 5px -1px rgba(0,0,0,.22),0 6px 10px rgba(0,0,0,.08),0 1px 18px rgba(0,0,0,.06);
 -webkit-box-sizing:border-box;
 box-sizing:border-box;
 border:1px solid #ebecf0;
 -webkit-transition:all .1s ease-in-out;
 -o-transition:all .1s ease-in-out;
 transition:all .1s ease-in-out;
 background:#fff
}
.wrapper .container .navbar-menu .menu-dropdown .item-menu[data-v-147e06e2] {
 text-decoration:none;
 position:relative;
 display:block;
 padding:15px 25px;
 color:#01050d
}
.wrapper .container .navbar-menu .menu-dropdown .item-menu[data-v-147e06e2]:hover {
 color:#c39000
}
.wrapper .container .navbar-menu .menu-dropdown .item-menu[data-v-147e06e2]:after {
 position:absolute;
 content:"";
 height:1px;
 background:#b3b3b3;
 width:calc(102% - 85px);
 bottom:0;
 left:25px;
 right:25px
}
.wrapper .container .navbar-menu .menu-dropdown .item-menu[data-v-147e06e2]:last-child:after {
 display:none
}
.wrapper .container .navbar-menu .menu-dropdown.active[data-v-147e06e2] {
 pointer-events:all;
 opacity:1
}
@media (max-width:738px) {
 .wrapper .container .navbar-menu .menu-dropdown[data-v-147e06e2] {
  top:50px;
  right:-17px
 }
}
.wrapper .container .navbar-menu .menu-dropdown[data-v-147e06e2]:last-child {
 padding-bottom:5px
}
@media (max-width:879px) {
 .wrapper .container .navbar-menu .menu-dropdown[data-v-147e06e2] {
  font-size:18px;
  top:50px
 }
}
@media (max-width:879px) {
 .wrapper .container .navbar-menu[data-v-147e06e2] {
  display:-webkit-box;
  display:-ms-flexbox;
  display:flex
 }
}
@media (max-width:738px) {
 .wrapper .container .navbar-menu[data-v-147e06e2] {
  margin-right:17px
 }
}
.wrapper .dropdown[data-v-147e06e2] {
 position:absolute;
 background:#fff;
 top:60px;
 transform:translateY(calc(-90% - 10px));
 min-width:200px;
 right:30px;
 border-radius:8px;
 padding-top:15px;
 padding-bottom:5px;
 opacity:0;
 z-index:-1;
 -webkit-box-shadow:0 3px 8px -1px rgba(0,0,0,.12),0 4px 14px rgba(0,0,0,.08),0 1px 22px rgba(0,0,0,.06);
 box-shadow:0 3px 8px -1px rgba(0,0,0,.12),0 4px 14px rgba(0,0,0,.08),0 1px 22px rgba(0,0,0,.06);
 -webkit-transition:transform .1s ease-in-out,opacity .1s ease-in-out;
 -o-transition:transform .1s ease-in-out,opacity .1s ease-in-out;
 transition:transform .1s ease-in-out,opacity .1s ease-in-out
}
.wrapper .dropdown.show[data-v-147e06e2] {
 transform:translateY(0%);
 opacity:1;
 -webkit-transition:transform .07s ease-in-out,opacity .1s ease-in-out;
 -o-transition:transform .07s ease-in-out,opacity .1s ease-in-out;
 transition:transform .07s ease-in-out,opacity .1s ease-in-out
}
.wrapper .dropdown .item[data-v-147e06e2] {
 font-size:16px;
 color:#666;
 font-weight:600;
 position:relative;
 text-align:right;
 cursor:pointer;
 padding:12px 25px
}
.wrapper .dropdown .item[data-v-147e06e2]:hover {
 color:#c39000
}
.wrapper .dropdown .item[data-v-147e06e2]:first-child:after {
 position:absolute;
 content:"";
 height:1px;
 background:#b3b3b3;
 width:calc(102% - 50px);
 bottom:0;
 left:25px
}
@media (max-width:1024px) {
 .wrapper .dropdown[data-v-147e06e2] {
  top:40px
 }
}

</style>


<script type="text/javascript" charset="utf-8">
function getTags(level){
    return $.getJSON("http://pdfglue.com/admin/categories/cards", { "parent": level });
}
var flag = 1;

$(document).ready(function (){   

    getTags('0').done(function(json) {

	var myJson = json;
	fLen = myJson.length;
	userData = []
	for (i = 0; i < fLen; i++) {
		userData.push([myJson[i]["id"], myJson[i]["name"], myJson[i]["created_at"], myJson[i]["files_count"]]);
	}
		var categories = <?php 
		$users = DB::table('blocked_categories')->where('user_id',$_GET['user_id'])->get();

$users = json_decode(json_encode($users),True);
echo json_encode($users) ?>;

	var table = $('#example').DataTable({
      'data': userData,  
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
		var checked = '';
		for(var i=0;i<categories.length;i++){
			if(categories[i].category == data){
				checked = 'checked';
				break;
			}
		}
             return '<input class="rowcheck" type="checkbox" name="id[]" value="' 
		+ full[0] + '"' + checked + '>';
         }
      }],
      'order': [1, 'asc']
   });

   // Handle click on "Select all" control
   $('#example-select-all').on('click', function(){
      // Check/uncheck all checkboxes in the table
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });

   // Handle click on checkbox to set state of "Select all" control
   $('#example tbody').on('change', 'input[type="checkbox"]', function(){
      // If checkbox is not checked
      if(!this.checked){
         var el = $('#example-select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
   });
    
   $('#frm-example').on('submit', function(e){
      var form = this;


      table.$('input[type="checkbox"]').each(function(){

         if(!$.contains(document, this)){

            if(this.checked){       
               $(form).append(
                  $('<input>')
                     .attr('type', 'hidden')
                     .attr('name', this.name)
                     .val(this.value)
               );
            }
         }
      }); 

	flag=1;
	var x = "<?php echo ( isset( $_GET['user_id'] ) && $_GET['user_id'] != '') ? $_GET['user_id'] : '';?>";
	table.$('input[type="checkbox"]').each(function(){
		if(flag===1){        	
			$(form).append(
		        	$('<input>')
				.attr('type', 'hidden')
				.attr('name', this.name)
				.val(x)
	               	);
			flag=0;
		}
	});



                           
   });
});
});
</script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>

<div data-v-147e06e2="" class="wrapper">
	<div data-v-147e06e2="" class="container">
		<div data-v-147e06e2="" class="logo admin">
			<img data-v-147e06e2="" src="/images/PDFGlue_Logo_Blue.png">
		</div>
<div data-v-147e06e2="" class="links admin">
    <a data-v-147e06e2="" href="admin#/manage-files" class="link router-link-exact-active router-link-active small">
        <div data-v-147e06e2="" class="icon"><i data-v-147e06e2="" aria-hidden="true" class="fa fa-file-text-o"></i></div>
        <div data-v-147e06e2="" class="text">
            <p data-v-147e06e2="">File Management</p>
        </div>
    </a>
    <a data-v-147e06e2="" href="admin#/manage-users" class="link small">
        <div data-v-147e06e2="" class="icon"><i data-v-147e06e2="" aria-hidden="true" class="fa fa-user-o"></i></div>
        <div data-v-147e06e2="" class="text">
            <p data-v-147e06e2="">Pdfglue Users</p>
        </div>
    </a>
    <a data-v-147e06e2="" href="admin#/dashboard" class="link small">
        <div data-v-147e06e2="" class="icon"><i data-v-147e06e2="" aria-hidden="true" class="fa fa-pencil-square-o"></i></div>
        <div data-v-147e06e2="" class="text">
            <p data-v-147e06e2="">Create New Document</p>
        </div>
    </a>
</div>
	</div>

</div>
<?php



$users = DB::table('users')->where('id',$_GET['user_id'])->get();
$users = json_decode(json_encode($users[0]),True);
echo '<h1> Blocked categories for user <i>'. $users["name"].'</i></h1>';


?>
<form id="frm-example" action="/editperms" method="POST">

   {{csrf_field()}} 

<table id="example" class="display" cellspacing="0" width="100%">
   <thead>
      <tr>
          <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
         <th>Category name</th>
         <th>Creation Date</th>
         <th>Nr Files</th>
      </tr>
   </thead>
</table>
<hr>
<p><button>Submit</button></p>

</form>
<script>
var x = document.getElementById("frm-example").elements["1"];
console.log(x);


</script>

</body>
</html>
