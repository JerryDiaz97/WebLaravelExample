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
                                            </button>
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
            changePage(page, find, criterion) {
                let me  = this;
                //Update to the current page
                me.pagination.current_page = page;
                //Send a petition to view the page data
                me.listEntry(page, find, criterion);
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