<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Example Listing table -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="openModal('client','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="namec">Nombre</option>
                                      <option value="doc_num">Documento</option>
                                      <option value="email">Email</option>
                                      <option value="phone_num">Teléfono</option>
                                    </select>
                                    <input type="text" v-model="find" @keyup.enter="listClient(1,find,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listClient(1,find,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo de documento</th>
                                    <th>Número</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="client in arrayClient" :key="client.id">
                                    <td>
                                        <button type="button" @click="openModal('client','update',client)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>&nbsp;
                                        <template v-if="client.condition">
                                            <button type="button" class="btn btn-danger btn-sm" @click="deactivateUser(client.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activateUser(client.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template> 
                                    </td>
                                    <td v-text="client.namec"></td>
                                    <td v-text="client.type_doc"></td>
                                    <td v-text="client.doc_num"></td>
                                    <td v-text="client.address"></td>
                                    <td v-text="client.phone_num"></td>
                                    <td v-text="client.email"></td> 
                                    <td v-text="client.user_name"></td>
                                    <td v-text="client.namer"></td>                                              
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1,find,criterion)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="changePage(page,find,criterion)" v-text="page"></a>
                                </li> 
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1,find,criterion)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- End listing table example -->
            </div>
            <!--Start of the modal add/update-->
            <div class="modal fade" tabindex="-1" :class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titleModal"></h4>
                            <button type="button" class="close" @click="closeModal()" aria-label="close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="namec" class="form-control" placeholder="Nombre del cliente">                                       
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de documento</label>
                                    <div class="col-md-9">
                                        <select v-model="type_doc" class="form-control">
                                            <option value="DNI">Identificación</option>
                                            <option value="LIC">Licencia de conducir</option>
                                            <option value="PASS">Pasaporte</option>
                                            <option value="RUC">RUC</option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número de documento</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="doc_num" class="form-control" placeholder="Número de documento">                                       
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="address" class="form-control" placeholder="Dirección">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="phone_num" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Rol (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="id_role">
                                            <option value="0">Seleccione un Rol</option>
                                            <option v-for="role in arrayRole" :key="role.id" :value="role.id" v-text="role.namer"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Usuario (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="user_name" class="form-control" placeholder="Nombre de Usuario">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Password (*)</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Contraseña de Acceso">
                                    </div>
                                </div>
                                <div v-show="errorClient" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsnClient" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerClient()">Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateClient()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Modal end-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                client_id: 0,
                namec : '',
                type_doc : 'DNI',
                doc_num : '',
                address : '',
                phone_num : '',
                email : '',
                user_name : '',
                password : '',
                id_role : 0,
                arrayClient : [],
                arrayRole : [],
                modal : 0,
                titleModal : '',
                typeAction : 0,
                errorClient : 0,
                errorShowMsnClient : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterion : 'namec',
                find : ''
            }
        },
        mounted() {
            this.listClient(1,this.find,this.criterion);
        },
        methods : {
            listClient (page, find, criterion){
                const axios = require('axios');
                let me=this;
                var url = '/user?page=' + page +'&find=' + find + '&criterion=' + criterion;

                axios.get(url).then(function (response) {
                    var answer = response.data
                    console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arrayClient = answer.clients.data;
                    me.pagination = answer.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
            },

            selectRole() {
                 const axios = require('axios');
                let me=this;
                var url = '/role/selectRole';

                axios.get(url).then(function (response) {
                    var answer = response.data
                    console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arrayRole = answer.roles;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
            },

            changePage(page, find, criterion) {
                let me  = this;
                //Update to the current page
                me.pagination.current_page = page;
                //Send a petition to view the page data
                me.listClient(page, find, criterion);

            },

            registerClient(){
                if (this.validateClient()){
                    return;
                }
                
                let me = this;

                axios.post('/user/register',{
                    'namec': this.namec,
                    'type_doc': this.type_doc,
                    'doc_num': this.doc_num,
                    'address': this.address,
                    'phone_num': this.phone_num,
                    'email': this.email,
                    'user_name': this.user_name,
                    'password': this.password,
                    'id_role': this.id_role

                }).then(function (response) {
                    me.closeModal();
                    me.listClient(1,'','namec');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            updateClient(){
                if (this.validateClient()){
                    return;
                }
                
                let me = this;

                axios.put('/user/update',{
                    'namec': this.namec,
                    'type_doc': this.type_doc,
                    'doc_num': this.doc_num,
                    'address': this.address,
                    'phone_num': this.phone_num,
                    'email': this.email,
                    'user_name': this.user_name,
                    'password': this.password,
                    'id_role': this.id_role,
                    'id': this.client_id 

                }).then(function (response) {
                    me.closeModal();
                    me.listClient(1,'','namec');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            validateClient(){
                this.errorClient = 0;
                this.errorShowMsnClient = [];

                if (!this.namec) this.errorShowMsnClient.push("El nombre del cliente no puede estar vacío");
                if (!this.user_name) this.errorShowMsnClient.push("El nombre de usuario no puede estar vacío");
                if (!this.password) this.errorShowMsnClient.push("La contraseña no puede estar vacío");
                if (this.id_role==0) this.errorShowMsnClient.push("Debe asignar un rol al usuario");

                if (this.errorShowMsnClient.length) this.errorClient = 1;

                return this.errorClient;
            },

            deactivateUser(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Realmente quiere desactivar el Usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/deactivate',{
                        'id': id

                    }).then(function (response) {
                        me.listClient(1,'','namec');
                        swalWithBootstrapButtons.fire(
                        'Desactivado',
                        'Se desactivo el registro',
                        'success'
                    )

                    }).catch(function (error) {
                        console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'error'
                    )
                }
                })
            },

            activateUser(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Realmente quiere activar este Usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/activate',{
                        'id': id

                    }).then(function (response) {
                        me.listClient(1,'','namec');
                        swalWithBootstrapButtons.fire(
                        'Activado',
                        'Se activo el registro',
                        'success'
                    )

                    }).catch(function (error) {
                        console.log(error);
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'error'
                    )
                }
                })
            },

            closeModal(){
                this.modal=0;
                this.titleModal='';
                this.namec='';
                this.type_doc='DNI';
                this.doc_num='';
                this.address='';
                this.phone_num='';
                this.email='';
                this.user_name='';
                this.password='';
                this.id_role=0;
                this.errorClient=0;
            },
            openModal(model, action, data = []){
                this.selectRole();
                switch(model){
                    case "client":
                    {
                        switch(action){
                            case 'register':
                            {
                                this.modal = 1;
                                this.titleModal = 'Registrar Usuario';
                                this.namec='';
                                this.type_doc='DNI';
                                this.doc_num='';
                                this.address='';
                                this.phone_num='';
                                this.email='';
                                this.user_name='';
                                this.password='';
                                this.id_role=0;
                                this.typeAction = 1;
                                break;
                            }
                            case 'update':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.titleModal = 'Actualizar Usuario';
                                this.typeAction = 2;
                                this.client_id = data['id'];
                                this.namec = data['namec'];
                                this.type_doc = data['type_doc'];
                                this.doc_num = data['doc_num'];
                                this.address = data['address'];
                                this.phone_num = data['phone_num'];
                                this.email = data['email'];
                                this.user_name = data['user_name'];
                                this.password = data['password'];
                                this.id_role = data['id_role'];
                                break;  
                            }
                        }
                    }
                }
            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>