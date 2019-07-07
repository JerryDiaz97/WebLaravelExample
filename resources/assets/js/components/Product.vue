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
                        <i class="fa fa-align-justify"></i> Productos
                        <button type="button" @click="openModal('product','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="name">Nombre</option>
                                      <option value="description">Descripción</option>
                                    </select>
                                    <input type="text" v-model="find" @keyup.enter="listProduct(1,find,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listProduct(1,find,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código de barras</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio de venta</th>
                                    <th>Stock</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in arrayProduct" :key="product.id">
                                    <td>
                                        <button type="button" @click="openModal('product','update',product)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="product.conditionProd">
                                            <button type="button" class="btn btn-danger btn-sm" @click="deactivateProduct(product.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activateProduct(product.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="product.code"></td>
                                    <td v-text="product.nameProd"></td>
                                    <td v-text="product.name"></td>
                                    <td v-text="product.sale_price"></td>
                                    <td v-text="product.stock"></td>
                                    <td v-text="product.descriptionProd"></td>
                                    <td>
                                        <div v-if="product.conditionProd">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idcategory">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="category in arrayCategory" :key="category.id" :value="category.id" v-text="category.name"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="code" class="form-control" placeholder="Código de barras">
                                            <barcode :value="code" :options="{format: 'EAN-13'}">
                                                Generando Código de Barras
                                            </barcode>
                                        </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="nameProd" class="form-control" placeholder="Nombre del producto">
                                        </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Precio de Venta</label>
                                        <div class="col-md-9">
                                        <input type="number" v-model="sale_price" class="form-control" placeholder="">
                                        </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                        <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="">
                                        </div>
                                </div>
                                <div class="form-group row">
                                            <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                            <div class="col-md-9">
                                                <input type="email" v-model="descriptionProd" class="form-control" placeholder="Ingrese descripción">
                                            </div>
                                </div>
                                <div v-show="errorProduct" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsnProduct" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerProduct()">Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateProduct()">Actualizar</button>
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
import VueBarcode from 'vue-barcode';
    export default {
        data (){
            return {
                product_id : 0,
                idcategory : 0,
                category_name : '',
                code : '',
                nameProd : '',
                sale_price : 0,
                stock : 0,
                descriptionProd : '',
                arrayProduct : [],
                modal : 0,
                titleModal : '',
                typeAction : 0,
                errorProduct : 0,
                errorShowMsnProduct : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterion : 'name',
                find : '',
                arrayCategory : []
            }
        },


        mounted() {
            this.listProduct(1,this.find,this.criterion);
        },
        methods : {
            listProduct (page, find, criterion){
                const axios = require('axios');
                let me=this;
                var url = '/product?page=' + page +'&find=' + find + '&criterion=' + criterion;

                axios.get(url).then(function (response) {
                    var answer = response.data
                    console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arrayProduct = answer.products.data;
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
            selectCategory() {
                const axios = require('axios');
                let me=this;
                var url = '/category/selectCategory';

                axios.get(url).then(function (response) {
                    var answer = response.data
                    console.log(response)
                    //me.arrayProduct = response.data.products.data;
                    me.arrayCategory = answer.categories;
                    
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
                me.listProduct(page, find, criterion);

            },

            registerProduct(){
                if (this.validateProduct()){
                    return;
                }
                
                let me = this;

                axios.post('/product/register',{
                    'idcategory' :this.idcategory,
                    'code' : this.code,
                    'nameProd': this.nameProd,
                    'sale_price' : this.sale_price,
                    'stock' : this.stock,
                    'descriptionProd': this.descriptionProd
                }).then(function (response) {
                    me.closeModal();
                    me.listProduct(1,'','nameProd');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            updateProduct(){
                if (this.validateProduct()){
                    return;
                }
                
                let me = this;

                axios.put('/product/update',{
                    'idcategory' :this.idcategory,
                    'code' : this.code,
                    'nameProd': this.nameProd,
                    'sale_price' : this.sale_price,
                    'stock' : this.stock,
                    'descriptionProd': this.descriptionProd,
                    'id': this.product_id

                }).then(function (response) {
                    me.closeModal();
                    me.listProduct(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                });
            },

            deactivateProduct(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Realmente quiere desactivar la categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/product/deactivate',{
                        'id': id

                    }).then(function (response) {
                        me.listProduct(1,'','nameProd');
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

            activateProduct(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Realmente quiere Activar la categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/product/activate',{
                        'id': id

                    }).then(function (response) {
                        me.listCategory(1,'','nameProd');
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
            validateProduct(){
                this.errorProduct=0;
                this.errorShowMsnProduct =[];

                if(this.idcategory == 0) this.errorShowMsnProduct.push("Debe seleccionar una categoría");
                if (!this.nameProd) this.errorShowMsnProduct.push("El nombre del producto no puede estar vacío");
                if(!this.stock) this.errorShowMsnProduct.push("El stock del articulo debe ser un número y no puede estar vacío");
                if(!this.sale_price) this.errorShowMsnProduct.push("El precio de venta del articulo debe ser un número y no puede estar vacío");


                if (this.errorShowMsnProduct.length) this.errorProduct = 1;

                return this.errorProduct;
            },

            closeModal(){
                this.modal = 0;
                this.titleModal = '';
                this.idcategory = 0;
                this.category_name = '';
                this.code = '';
                this.nameProd='';
                this.sale_price = 0;
                this.stock = 0;
                this.descriptionProd = '';
                this.errorProduct = 0;
            },
            openModal(model, action, data = []){
                switch(model){
                    case "product":
                    {
                        switch(action){
                            case 'register':
                            {
                                this.modal = 1;
                                this.titleModal = 'Registrar Producto';
                                this.idcategory = 0;
                                this.category_name = '';
                                this.code = '';
                                this.nameProd='';
                                this.sale_price = 0;
                                this.stock = 0;
                                this.descriptionProd = '';
                                this.typeAction = 1;
                                break;
                            }
                            case 'update':
                            {
                                //console.log(data);
                                this.modal = 1;
                                this.titleModal = 'Actualizar Producto';
                                this.typeAction = 2;
                                this.product_id = data['id'];
                                this.idcategory = data['idcategory'];
                                this.code = data['code'];
                                this.nameProd = data['nameProd'];
                                this.sale_price = data['sale_price'];
                                this.stock = data['stock'];
                                this.descriptionProd = data['descriptionProd'];
                                break;  
                            }
                        }
                    }
                }
                this.selectCategory();
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

        components: {
            'barcode': VueBarcode
        }
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