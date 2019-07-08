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
                    <template v-if="listing==1">
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
                                            <button type="button" @click="viewIncome(entry.id)" class="btn btn-success btn-sm">
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
                    <template v-else-if="listing==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select :on-search="selectProvider" label="namec" 
                                    :options="arrayProvider" placeholder="Buscar Proveedores..."
                                    :onChange="getProviderData">

                                    </v-select>
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
                            <div class="col-md-12">
                                <div v-show="errorEntry" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsnEntry" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Artículo <span style="color:red;" v-show="id_product==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="findProduct" placeholder="Ingrese artículo">
                                        <button @click="openModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="product">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color:red;" v-show="price==0">(*Ingrese Precio)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="price">                                      
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color:red;" v-show="amount==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="amount">                                      
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="addDetail()" class="btn btn-success form-control btnadd"><i class="icon-plus"></i></button>                                     
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
                                    <tbody v-if="arrayDetail.length">
                                        <tr v-for="(detail,index) in arrayDetail" :key="detail.id">
                                            <td>
                                                <button @click="deleteDetail(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detail.product">

                                            </td>
                                            <td>
                                                <input v-model="detail.price" type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="detail.amount" type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                {{detail.price*detail.amount}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total del Impuesto:</strong></td>
                                            <td>$ {{taxestotal = ((total*taxes)/(1+taxes)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total = calculateTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay productos agregados
                                            </td>
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
                    <!--Show Income-->
                    <template v-else-if="listing==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="provider">
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="taxes"></p>
                            </div>                            
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="type_voucher"></p>
                                </div>                       
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="voucher_series"></p>
                                </div>                       
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <p v-text="voucher_num"></p>
                                </div>                       
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th> 
                                            <th>Precio</th>  
                                            <th>Cantidad</th> 
                                            <th>Subtotal</th>
                                        </tr> 
                                    </thead>
                                    <tbody v-if="arrayDetail.length">
                                        <tr v-for="detail in arrayDetail" :key="detail.id">                                            
                                            <td v-text="detail.product">
                                            </td>
                                            <td v-text="detail.price">
                                            </td>
                                            <td v-text="detail.amount">
                                            </td>
                                            <td>
                                                {{detail.price*detail.amount}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total del Impuesto:</strong></td>
                                            <td>$ {{taxestotal = ((total*taxes)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                No hay productos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="hideDetail()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!--End Show income-->
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
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterionP">
                                        <option value="name">Nombre</option>
                                        <option value="description">Descripción</option>
                                        <option value="code">Código</option>
                                        </select>
                                        <input type="text" v-model="findP" @keyup.enter="listProduct(findP,criterionP)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listProduct(findP,criterionP)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-resposive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código de barras</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Precio de venta</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="product in arrayProduct" :key="product.id">
                                            <td>
                                                <button type="button" @click="addDetailModal(product)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button> 
                                            </td>
                                            <td v-text="product.code"></td>
                                            <td v-text="product.nameProd"></td>
                                            <td v-text="product.name"></td>
                                            <td v-text="product.sale_price"></td>
                                            <td v-text="product.stock"></td>
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
                            </div>
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
    import vSelect from 'vue-select';
    export default {
        data (){
            return {
                income_id: 0,
                id_provider : 0,
                provider : '',
                namec: '',
                type_voucher : 'BOLETA',
                voucher_series : '',
                voucher_num : '',
                taxes : 0.16,
                total : 0.0,
                taxestotal : 0.0,
                subtotal : 0.0,
                arrayEntry: [],
                arrayProvider: [],
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
                find : '',
                criterionP : 'nameProd',
                findP : '',  
                arrayProduct : [],
                id_product : 0,
                code : '',
                product : '',
                price : 0,
                amount : 0
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
            selectProvider(search, loading) {
                const axios = require('axios');
                let me=this;
                loading(true)

                var url = '/provider/selectProvider?filter='+ search;
                axios.get(url).then(function (response) {
                    var answer = response.data
                    q: search
                    me.arrayProvider=answer.providers;
                    loading(false)
                    //console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
            },
            getProviderData(val1){
                let me = this;
                me.loading = true;
                me.id_provider = val1.id;

            },
            findProduct(){
                let me = this;
                var url = '/product/findProduct?filter=' + me.code;

                axios.get(url).then(function (response) {
                    var answer = response.data;
                    me.arrayProduct = answer.product;

                    if(me.arrayProduct.length>0){
                        me.product = me.arrayProduct[0]['nameProd'];
                        me.id_product = me.arrayProduct[0]['id'];
                    }
                    else{
                        me.product = 'No existe artículo';
                        me.id_product = 0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })                
            },
            changePage(page, find, criterion) {
                let me  = this;
                //Update to the current page
                me.pagination.current_page = page;
                //Send a petition to view the page data
                me.listEntry(page, find, criterion);
            },
            finder(id){
                var sw = 0;
                for(var i=0; i < this.arrayDetail.length; i++){
                    if(this.arrayDetail[i].id_product == id){
                        sw = true;
                    }
                }
                return sw;
            },
            deleteDetail(index){
                let me = this;
                me.arrayDetail.splice(index, 1);

            },
            addDetail(){
                let me = this;
                if(me.id_product == 0 || me.amount == 0 || me.price == 0){   
                }
                else{
                    if(me.finder(me.id_product)){
                        swal({
                            type: 'error',
                            title : 'Error...',
                            text : 'Ese artículo ya se encuentra agregado!',

                        })
                    }
                    else{
                         me.arrayDetail.push({
                        id_product : me.id_product,
                        product : me.product,
                        amount : me.amount,
                        price : me.price
                    });
                    me.code = "";
                    me.id_product = 0;
                    me.product = "";
                    me.amount = 0;
                    me.price = 0;
                    }       
                }                            
            },
            addDetailModal(data = []){
                let me = this;
                if(me.finder(data['id'])){
                    swal({
                    type: 'error',
                        title : 'Error...',
                        text : 'Ese artículo ya se encuentra agregado!',
                    })
                }
                else{
                    me.arrayDetail.push({
                    id_product : data['id'],
                    product : data['nameProd'],
                    amount : 1,
                    price : 1
                });
                }      
            },
            listProduct (find, criterion){
                const axios = require('axios');
                let me=this;
                var url = '/product/listProduct?page='+ find + '&criterion=' + criterion;

                axios.get(url).then(function (response) {
                    var answer = response.data
                    //console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arrayProduct = answer.products.data;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
            },
            registerEntry(){
                if (this.validateEntry()){
                    return;
                }                
                let me = this;
                axios.post('/entry/register',{
                    'id_provider' : this.id_provider,
                    'type_voucher': this.type_voucher,
                    'voucher_series': this.voucher_series,
                    'voucher_num': this.voucher_num,
                    'taxes' : this.taxes,
                    'total' : this.total,
                    'data' : this.arrayDetail
                    
                }).then(function (response) {
                    me.listing=1;
                    me.listEntry(1,'','voucher_num');
                    me.id_provider=0;
                    me.type_voucher='BOLETA';
                    me.voucher_series='';
                    me.voucher_num='';
                    me.taxes=0.16;
                    me.total=0.0;
                    me.id_product=0;
                    me.product='';
                    me.amount=0;
                    me.price=0;
                    me.arrayDetail=[];

                }).catch(function (error) {
                    console.log(error);
                });
            },
            validateEntry(){
                this.errorEntry = 0;
                this.errorShowMsnEntry = [];

                if(this.id_provider == 0) this.errorShowMsnEntry.push("Seleccione un Proveedor");
                if(this.type_voucher == 0) this.errorShowMsnEntry.push("Seleccione el Tipo de Comprobante");
                if(!this.voucher_num) this.errorShowMsnEntry.push("Ingrese el número del Comprobante");
                if(!this.taxes) this.errorShowMsnEntry.push("Ingrese el impuesto de Compra");
                if(this.arrayDetail.length<=0) this.errorShowMsnEntry.push("Ingrese el Detalle");

                if (this.errorShowMsnEntry.length) this.errorEntry = 1;
                return this.errorEntry;
            },
            deactivateEntry(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })
                swalWithBootstrapButtons.fire({
                title: 'Realmente quiere desactivar el ingreso?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put('/entry/deactivate',{
                        'id': id
                    }).then(function (response) {
                        me.listEntry(1,'','voucher_num');
                        swalWithBootstrapButtons.fire(
                        'Anulado',
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
                    )
                }
                })
            },
            showDetail(){
                let me = this;

                this.listing=0;
                me.id_provider=0;
                me.type_voucher='BOLETA';
                me.voucher_series='';
                me.voucher_num='';
                me.taxes=0.16;
                me.total=0.0;
                me.id_product=0;
                me.product='';
                me.amount=0;
                me.price=0;
                me.arrayDetail=[];
            },
            hideDetail(){
                this.listing=1;
            },
            viewIncome(id){
                const axios = require('axios');
                let me = this;
                this.listing=2;

                //Get income data
                var arrayIncomeTem=[];
                var url = '/entry/getHeader?id=' + id;
                axios.get(url).then(function (response) {
                    var answer = response.data
                    //console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    arrayIncomeTem = answer.entry;

                    me.provider = arrayIncomeTem[0]['namec'];
                    me.type_voucher = arrayIncomeTem[0]['type_voucher'];
                    me.voucher_series = arrayIncomeTem[0]['voucher_series'];
                    me.voucher_num = arrayIncomeTem[0]['voucher_num'];
                    me.taxes = arrayIncomeTem[0]['taxes'];
                    me.total = arrayIncomeTem[0]['total'];
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });

                //Get details data

                var urld = '/entry/getDetails?id=' + id;
                axios.get(urld).then(function (response) {
                    var answer = response.data
                    //console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arrayDetail = answer.details;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });

            },
            closeModal(){
                this.modal=0;
                this.titleModal='';                
            },
            openModal(){
                this.arrayProduct = [];                
                this.modal = 1;
                this.titleModal = 'Seleccione uno o varios productos';                              
            }
        },
        components : {
            vSelect
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
            },
            calculateTotal: function(){
                var resultC = 0.0;
                for(var i=0; i<this.arrayDetail.length; i++){
                    resultC = resultC + (this.arrayDetail[i].price*this.arrayDetail[i].amount)
                }
                return resultC;
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