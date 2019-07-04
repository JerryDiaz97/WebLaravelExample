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
                        <i class="fa fa-align-justify"></i> Roles
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="namer">Nombre</option>
                                      <option value="description">Descripción</option>
                                    </select>
                                    <input type="text" v-model="find" @keyup.enter="listRole(1,find,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listRole(1,find,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role in arrayRole" :key="role.id">                                    
                                    <td v-text="role.namer"></td>
                                    <td v-text="role.description"></td>
                                    <td>
                                        <div v-if="role.condition">
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
        </main>
</template>

<script>
    export default {
        data (){
            return {
                role_id: 0,
                namer : '',
                description : '',
                arrayRole : [],
                modal : 0,
                titleModal : '',
                typeAction : 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterion : 'namer',
                find : ''
            }
        },
        mounted() {
            this.listRole(1,this.find,this.criterion);
        },
        methods : {
            listRole (page, find, criterion){
                const axios = require('axios');
                let me=this;
                var url = '/role?page=' + page +'&find=' + find + '&criterion=' + criterion;

                axios.get(url).then(function (response) {
                    var answer = response.data
                    console.log(response.data)
                    //me.arrayCategory = response.data.categories.data;
                    me.arrayRole = answer.roles.data;
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
                me.listRole(page, find, criterion);

            },

        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.oofset;
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
        }
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