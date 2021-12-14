<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();
if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}

require_once('layout/header.php');
require_once('layout/navbar.php');

$rVault = new VaultRoom();
$mVaultList = $rVault->getAllOfInfo();
$mVaultList = $mVaultList->results();
?>

<div class="content-wrapper">
    <div class="card card-primary card-outline card-outline-tabs ml-3 mr-3">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="vault" role="tablist">
                <li class="nav-item">
                    <a class="nav-link " id="vault_plants_tab" href="plantsVault.php" role="tab" >Vault Plants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="vault_rooms_tab"  aria-controls="vault_rooms_tab" aria-selected="true">Vault Rooms</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="vault_rooms_content" role="tabpanel" aria-labelledby="vault_rooms_content">
                    <!-- Vault Rooms Section-->
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-primary" id="btn_addVaultRoom">
                                        Add Vault Room
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="example1" class="table table-bordered table-striped" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="display:none">id</th>
                                    <th>Name</th>
                                    <th ></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if($mVaultList){
                                    $k = 0;
                                    foreach ($mVaultList as $mVault):
                                        $k++;
                                        ?>

                                        <tr>
                                            <td><?php echo $k?></td>
                                            <td style="display:none" class="td_id"><?php echo $mVault->id?></td>
                                            <td class="td_name"><?php echo $mVault->name?></td>
                                            <td style="text-align: center">
                                                <a class="btn bg-gradient-green btn-sm" id="btn_editVaultRoom" data-target="#modal-add-vault-room" href="#modal-add-vault-room" data-toggle="modal">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </a>
                                                <a class="btn bg-gradient-danger btn-sm" href="../Logic/saveVaultRooms.php?act=delete&id=<?php echo $mVault->id?>&cat=users" >
                                                    <i class="fas fa-trash"></i>
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach;} ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<!-- Vault room modal (Add/Edit) -->
<div class="modal fade" id="modal-add-vault-room">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Vault Room</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="../Logic/saveVaultRooms.php" enctype='multipart/form-data' id="editVaultRoomFormValidate">
                <!--body-->
                <div class="modal-body">
                        <input name="cat" type="hidden" value="users">
                        <input name="id" id="id" type="hidden" value="">
                        <input name="act" id="act" type="hidden" value="">
                        <label>Name</label>
                        <div class="form-group input-group mb-3">
                            <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                </div>
                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="btn_saveVaultRoom" class="btn btn-primary" value="Save"></input>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.modal -->


<script>
    $("table#example1").DataTable({
        stateSave: true,
    });
    $(document).ready(function(){
        $('#btn_addVaultRoom').click(function(){
            var act = 'add'
            $('#act').val(act);
            $('#modal-add-vault-room').modal('show');
        })
        //reference url https://jsfiddle.net/1s9u629w/1/
        $('tr #btn_editVaultRoom').click(function() {
            var $row = $(this).closest('tr');
            var id = $row.find('.td_id').text();
            var act = "edit";
            var name =  $row.find('.td_name').text();
            $("#id").val(id);
            $("#act").val(act);
            $("#name").val(name);
        });

        //modal close
        $('#modal-add-user').on('hidden.bs.modal', function () {
            $("#name").val("");
            $("#email").val("");
        })
        //submit
        $('input#btn_saveVaultRoom').click(function (event) {
            var name = $('input[name=name]').val();
            var act = $('input[name=act]').val();
            if (act == 'add'){
                if (name){
                    event.preventDefault();
                    $.ajax({
                        method:'POST',
                        url: '../Logic/saveVaultRooms.php',
                        data:{act:'validate', name:name},
                        success:function (data) {
                            var obj = JSON.parse(data);
                            if(obj == 'SameName'){
                                alert('Exist Same Name');
                            }else{
                                $('#editVaultRoomFormValidate').submit();
                            }
                        }
                    })
                }
            }
            if (act == 'edit'){
                $('#editVaultRoomFormValidate').submit();
            }
            return false;
        })
    });
</script>



<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>
