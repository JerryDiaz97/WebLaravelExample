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
                                            <button type="button" @click="pdfSale(sale.id)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button>
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
            pdfSale(id){
                window.open('http://localhost:8000/sale/pdf/'+ id + ',' + '_blank');
            },
            changePage(page, find, criterion) {
                let me  = this;
                //Update to the current page
                me.pagination.current_page = page;
                //Send a petition to view the page data
                me.listSale(page, find, criterion);
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