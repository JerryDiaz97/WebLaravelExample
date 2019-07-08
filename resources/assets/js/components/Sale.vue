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
                        <i class="fa fa-align-justify"></i> Ventas
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
                                      <option value="type_voucherS">Tipo de Comprobante</option>
                                      <option value="voucher_numS">Numero de Comprobante</option>
                                      <option value="date_hourS">Fecha y hora</option>
                                    </select>
                                    <input type="text" v-model="find" @keyup.enter="listSale(1,find,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listSale(1,find,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
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
                                    <tr v-for="sale in arraySale" :key="sale.id">
                                        <td>
                                            <button type="button" @click="viewSale(sale.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>&nbsp;
                                            <template v-if="sale.status=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="deactivateSale(sale.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="sale.user_name"></td>
                                        <td v-text="sale.namec"></td>
                                        <td v-text="sale.type_voucherS"></td>
                                        <td v-text="sale.voucher_seriesS"></td>
                                        <td v-text="sale.voucher_numS"></td>
                                        <td v-text="sale.date_hourS"></td>
                                        <td v-text="sale.totalS"></td>
                                        <td v-text="sale.taxesS"></td>
                                        <td v-text="sale.status"></td>                                            
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
                                    <label for="">Cliente(*)</label>
                                    <v-select
                                    :on-search="selectClient" 
                                    label="namec" 
                                    :options="arrayClient" 
                                    placeholder="Buscar Clientes..."
                                    :onChange="getClientData">

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="taxesS">
                            </div>                            
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="type_voucherS">
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
                                    <input type="text" class="form-control" v-model="voucher_seriesS" placeholder="000x">
                                </div>                       
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="voucher_numS" placeholder="000xx">
                                </div>                       
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorSale" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsnSale" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
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
                                    <label>Descuento</label>
                                    <input type="number" value="0" class="form-control" v-model="discount">                                      
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
                                            <th>Descuento</th> 
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
                                                <input v-model="detail.price" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detail.amount>detail.stock">Stock: {{detail.stock}}</span>
                                                <input v-model="detail.amount" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detail.discount>(detail.price*detail.amount)">Descuento superior</span>
                                                <input v-model="detail.discount" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{detail.price*detail.amount-detail.discount}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ </td>{{subtotal=(total-taxestotal).toFixed(2)}}
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total del Impuesto:</strong></td>
                                            <td>$ {{taxestotal = ((total*taxesS)/(1+taxesS)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total = calculateTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
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
                                <button type="button" class="btn btn-primary" @click="registerSale()">Registrar Venta</button>                                
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
                                    <label for="">Cliente</label>
                                    <p v-text="client">
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="taxesS"></p>
                            </div>                            
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="type_voucherS"></p>
                                </div>                       
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="voucher_seriesS"></p>
                                </div>                       
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <p v-text="voucher_numS"></p>
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
                                            <th>Descuento</th>  
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
                                            <td v-text="detail.discount">
                                            </td>
                                            <td>
                                                {{detail.price*detail.amount-detail.discount}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total del Impuesto:</strong></td>
                                            <td>$ {{taxestotal = ((totalS*taxesS)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{totalS}}</td>
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
    import Vue from 'vue';
    import vSelect from 'vue-select';
    Vue.component('v-select', vSelect);
    export default {
        data (){
            return {
                sale_id: 0,
                id_client : 0,
                client : '',
                namec: '',
                type_voucherS : 'BOLETA',
                voucher_seriesS : '',
                voucher_numS : '',
                taxesS : 0.16,
                totalS : 0.0,
                taxestotal : 0.0,
                subtotal : 0.0,
                arraySale: [],
                arrayClient: [],
                arrayDetail: [],
                listing : 1,
                modal : 0,
                titleModal : '',
                typeAction : 0,
                errorSale : 0,
                errorShowMsnSale : [],
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
                amount : 0,
                discount : 0,
                stock : 0
            }
        },
        mounted() {
            this.listSale(1,this.find,this.criterion);
        },
        methods : {
            listSale (page, find, criterion){
                const axios = require('axios');
                let me=this;
                var url = '/sale?page=' + page +'&find=' + find + '&criterion=' + criterion;
                axios.get(url).then(function (response) {
                    var answer = response.data
                    console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arraySale = answer.sales.data;
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
            selectClient(search, loading) {
                const axios = require('axios');
                let me=this;
                loading(true)

                var url = '/client/selectClient?filter='+ search;
                axios.get(url).then(function (response) {
                    var answer = response.data
                    q: search
                    me.arrayClient=answer.clients;
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
            getClientData(val1){
                let me = this;
                me.loading = true;
                me.id_client = val1.id;

            },
            findProduct(){
                let me = this;
                var url = '/product/findProductSale?filter=' + me.code;

                axios.get(url).then(function (response) {
                    var answer = response.data;
                    me.arrayProduct = answer.product;

                    if(me.arrayProduct.length>0){
                        me.product = me.arrayProduct[0]['nameProd'];
                        me.id_product = me.arrayProduct[0]['id'];
                        me.price = me.arrayProduct[0]['sale_price'];
                        me.stock = me.arrayProduct[0]['stock'];
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
                me.listSale(page, find, criterion);
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
                        if(me.amount>me.stock){
                            swal({
                            type: 'error',
                            title : 'Error...',
                            text : 'No hay stock disponible!',

                        })
                        }
                        else{
                            me.arrayDetail.push({
                            id_product : me.id_product,
                            product : me.product,
                            amount : me.amount,
                            price : me.price,
                            discount : me.discount,
                            stock : me.stock
                        });
                        me.code = "";
                        me.id_product = 0;
                        me.product = "";
                        me.amount = 0;
                        me.price = 0;
                        me.discount = 0;
                        me.stock = 0;
                        }       
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
                    price : data['sale_price'],
                    discount : 0,
                    stock : data['stock']
                });
                }      
            },
            listProduct (find, criterion){
                const axios = require('axios');
                let me=this;
                var url = '/product/listProductSale?page='+ find + '&criterion=' + criterion;

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
            registerSale(){
                if (this.validateSale()){
                    return;
                }                
                let me = this;
                axios.post('/sale/register',{
                    'id_client' : this.id_client,
                    'type_voucherS': this.type_voucherS,
                    'voucher_seriesS': this.voucher_seriesS,
                    'voucher_numS': this.voucher_numS,
                    'taxesS' : this.taxesS,
                    'totalS' : this.totalS,
                    'data' : this.arrayDetail
                    
                }).then(function (response) {
                    me.listing=1;
                    me.listSale(1,'','voucher_numS');
                    me.id_client=0;
                    me.type_voucherS='BOLETA';
                    me.voucher_seriesS='';
                    me.voucher_numS='';
                    me.taxesS=0.16;
                    me.totalS=0.0;
                    me.id_product=0;
                    me.product='';
                    me.amount=0;
                    me.price=0;
                    me.stock=0;
                    me.code='';
                    me.discount=0;
                    me.arrayDetail=[];

                }).catch(function (error) {
                    console.log(error);
                });
            },
            validateSale(){
                let me = this;
                me.errorSale = 0;
                me.errorShowMsnSale = [];
                var prod;

                me.arrayDetail.map(function(x){
                    if(x.amount>x.stock){
                        prod = x.product + " con stock insuficiente";
                        me.errorShowMsnSale.push(prod);
                    }
                });

                if(me.id_client == 0) me.errorShowMsnSale.push("Seleccione un Cliente");
                if(me.type_voucherS == 0) me.errorShowMsnSale.push("Seleccione el Tipo de Comprobante");
                if(!me.voucher_numS) me.errorShowMsnSale.push("Ingrese el número del Comprobante");
                if(!me.taxesS) me.errorShowMsnSale.push("Ingrese el impuesto de Compra");
                if(me.arrayDetail.length<=0) me.errorShowMsnSale.push("Ingrese el Detalle");

                if (me.errorShowMsnSale.length) me.errorSale = 1;
                return me.errorSale;
            },
            deactivateSale(id) {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                })
                swalWithBootstrapButtons.fire({
                title: 'Realmente quiere anular esta Venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put('/sale/deactivate',{
                        'id': id
                    }).then(function (response) {
                        me.listSale(1,'','voucher_numS');
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
                me.id_client=0;
                me.type_voucherS='BOLETA';
                me.voucher_seriesS='';
                me.voucher_numS='';
                me.taxesS=0.16;
                me.totalS=0.0;
                me.id_product=0;
                me.product='';
                me.amount=0;
                me.price=0;
                me.arrayDetail=[];
            },
            hideDetail(){
                this.listing=1;
            },
            viewSale(id){
                const axios = require('axios');
                let me = this;
                this.listing=2;

                //Get income data
                var arraySaleTem=[];
                var url = '/sale/getHeader?id=' + id;
                axios.get(url).then(function (response) {
                    var answer = response.data
                    //console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    arraySaleTem = answer.sale;

                    me.client = arraySaleTem[0]['namec'];
                    me.type_voucherS = arraySaleTem[0]['type_voucherS'];
                    me.voucher_seriesS = arraySaleTem[0]['voucher_seriesS'];
                    me.voucher_numS = arraySaleTem[0]['voucher_numS'];
                    me.taxesS = arraySaleTem[0]['taxesS'];
                    me.totalS = arraySaleTem[0]['totalS'];
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });

                //Get details data

                var urld = '/sale/getDetails?id=' + id;
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
                    resultC = resultC + (this.arrayDetail[i].price*this.arrayDetail[i].amount-this.arrayDetail[i].discount)
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