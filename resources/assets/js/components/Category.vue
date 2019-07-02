<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Tablero</li>
            </ol>
            <div class="container-fluid">
                <!-- Example Listing table -->
                <div class="card">
                    <div class="card-header">
                         <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" @click="openModal('category','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" id="opcion" name="opcion">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in arrayCategory" :key="category.id">
                                    <td>
                                        <button type="button" @click="openModal('category','update',category)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button type="button" class="btn btn-danger btn-sm">
                                          <i class="icon-trash"></i>
                                        </button>
                                    </td>
                                    <td v-text="category.name"></td>
                                    <td v-text="category.description"></td>
                                    <td>
                                        <div v-if="category.condition">
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
                                <li class="page-item">
                                    <a class="page-link" href="#">Ant</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">4</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Sig</a>
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
                            <button type="button" class="close" @click="closeModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de categoría">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorCategory" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsnCategory" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerCategory()">Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateCategory()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Modal end-->
            <!-- Start of the modal Delete -->
            <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Categoría</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar la categoría?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- End modal Delete -->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                category_id: 0,
                name : '',
                description : '',
                arrayCategory : [],
                modal : 0,
                titleModal : '',
                typeAction : 0,
                errorCategory : 0,
                errorShowMsnCategory : []
            }
        },
        methods : {
            listCategory (){
                let me=this;
                axios.get('/category').then(function (response) {
                    me.arrayCategory = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registerCategory(){
                if (this.validateCategory()){
                    return;
                }
                
                let me = this;

                axios.post('/category/register',{
                    'name': this.name,
                    'description': this.description
                }).then(function (response) {
                    me.closeModal();
                    me.listCategory();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validateCategory(){
                this.errorCategory=0;
                this.errorShowMsnCategory =[];

                if (!this.name) this.errorShowMsnCategory.push("El nombre de la categoría no puede estar vacío.");

                if (this.errorShowMsnCategory.length) this.errorCategory = 1;

                return this.errorCategory;
            },

            updateCategory(){
                if (this.validateCategory()){
                    return;
                }
                
                let me = this;

                axios.put('/category/update',{
                    'name': this.name,
                    'description': this.description,
                    'id': this.category_id

                }).then(function (response) {
                    me.closeModal();
                    me.listCategory();
                }).catch(function (error) {
                    console.log(error);
                });
            },

            closeModal(){
                this.modal=0;
                this.titleModal='';
                this.name='';
                this.description='';
            },
            openModal(model, action, data = []){
                switch(model){
                    case "category":
                    {
                        switch(action){
                            case 'register':
                            {
                                this.modal = 1;
                                this.titleModal = 'Registrar Categoría';
                                this.name= '';
                                this.description = '';
                                this.typeAction = 1;
                                break;
                            }
                            case 'update':
                            {
                                console.log(data);
                                this.titleModal = 'Actualizar Categoría';
                                this.typeAction = 2;
                                this.category_id = data['id'];
                                this.name = data['name'];
                                this.description = data['description'];
                                break;  
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listCategory();
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