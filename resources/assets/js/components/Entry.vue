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
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="showDetail()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!--List-->
                    <template v-if="listing">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="type_voucher">Tipo de Comprobante</option>
                                      <option value="voucher_num">Numero de Comprobante</option>
                                      <option value="date_hour">Fecha y hora</option>
                                    </select>
                                    <input type="text" v-model="find" @keyup.enter="listEntry(1,find,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listEntry(1,find,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo de Comprobante</th>
                                        <th>Serie del Comprobante</th>
                                        <th>Número del Comprobante</th>
                                        <th>Fecha y hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="entry in arrayEntry" :key="entry.id">
                                        <td>
                                            <button type="button" @click="openModal('entry','update',entry)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>&nbsp;
                                            <template v-if="entry.status=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="deactivateEntry(entry.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="entry.user_name"></td>
                                        <td v-text="entry.namec"></td>
                                        <td v-text="entry.type_voucher"></td>
                                        <td v-text="entry.voucher_series"></td>
                                        <td v-text="entry.voucher_num"></td>
                                        <td v-text="entry.date_hour"></td>
                                        <td v-text="entry.total"></td>
                                        <td v-text="entry.taxes"></td>
                                        <td v-text="entry.status"></td>                                            
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
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
                    </template>
                    <!--End List-->
                    <!--Detail-->
                    <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <select class="form-control">
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="taxes">
                            </div>                            
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="type_voucher">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>                       
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="voucher_series" placeholder="000x">
                                </div>                       
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="voucher_num" placeholder="000xx">
                                </div>                       
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Artículo</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="id_product" placeholder="Ingrese artículo">
                                        <button class="btn btn-primary">...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="price">                                      
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" value="0" class="form-control" v-model="amount">                                      
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnadd"><i class="icon-plus"></i></button>                                     
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th> 
                                            <th>Precio</th>  
                                            <th>Cantidad</th> 
                                            <th>Subtotal</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                Artículo n
                                            </td>
                                            <td>
                                                <input type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                $6.00
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total del Impuesto:</strong></td>
                                            <td>$ 1</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ 6</td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registerEntry()">Registrar Compra</button>                                
                            </div>
                        </div>
                    </div>
                    </template>
                    <!--End Detail-->

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
                id_entry: 0,
                id_provider : 0,
                namec: '',
                type_voucher : 'BOLETA',
                voucher_series : '',
                voucher_num : '',
                taxes : 0.16,
                total : 0.0,
                arrayEntry: [],
                arrayDetail: [],
                listing : 1,
                modal : 0,
                titleModal : '',
                typeAction : 0,
                errorEntry : 0,
                errorShowMsnEntry : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterion : 'voucher_num',
                find : ''
            }
        },
        mounted() {
            this.listEntry(1,this.find,this.criterion);
        },
        methods : {
            listEntry (page, find, criterion){
                const axios = require('axios');
                let me=this;
                var url = '/entry?page=' + page +'&find=' + find + '&criterion=' + criterion;
                axios.get(url).then(function (response) {
                    var answer = response.data
                    console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arrayEntry = answer.entries.data;
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
                me.listEntry(page, find, criterion);
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
            showDetail(){
                this.listing=0;
            },
            hideDetail(){
                this.listing=1;
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
    @media (min-width: 600px){
        .btnadd{
            margin-top: 2rem;
        }
    }
</style>