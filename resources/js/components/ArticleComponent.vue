<template>
    <div>
        <h1 class="text-center">Manage Articles</h1>

      <!-- Button to Open the Modal -->
<button  @click="modify=false; abrirModal();" type="button" class="btn btn-primary my-4"  >
  New Article
</button>

<!-- The Modal -->
<div class="modal" :class="{mostrar:modal}">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">{{titleModal}}</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form>
            <div class="my-4">
            <label class="form-control" for="name">Name</label>
            <input  v-model="article.name" class="form-control" type="text" placeholder="Name..." id="name">
            <span class="text-danger" v-if="errors.name">{{errors.name[0]}}</span>
            </div>

            <div class="my-4">
            <label class="form-control" for="description">Description</label>
            <input v-model="article.description" class="form-control" type="text" placeholder="Description..." id="description">
            <span class="text-danger" v-if="errors.description">{{errors.description[0]}}</span>
            </div>

            <div class="my-4">
            <label class="form-control" for="stock">Stock</label>
            <input v-model="article.stock" class="form-control" type="number" placeholder="Stock..." id="stock">
            <span class="text-danger" v-if="errors.stock">{{errors.stock}}</span>
            </div>
        </form>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button @click="cerrarModal();" type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
        <button @click="guardar();" type="button" class="btn btn-success" data-dismiss="modal" >Save</button>
      </div>

    </div>
  </div>
</div>

    <table class="table table-striped">
   <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
      <th scope="col">Stock</th>
      <th scope="col" colspan="2" class="text-center">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="art in articles.data" :key="art.id">
      <th scope="row">{{art.id}}</th>
      <td>{{art.name}}</td>
      <td>{{art.description}}</td>
      <td>{{art.stock}}</td>

      <td>
        <button @click="modify=true; abrirModal(art);" class="btn btn-warning">Edit</button>
      </td>
      <td>
        <button @click="eliminar(art.id)" class="btn btn-danger">Delete</button>
      </td>
    </tr>
    
  </tbody>
</table>

      <div class="row">
        <div class="col-4 md-4">

        </div>

        <div class="col-2 md-2">

        </div>

        <div class="col-6 md-6">
          <nav aria-label="Page navigation example">
           <ul class="pagination">
            <li class="page-item">
              <a class="page-link"  :class="{disabled :pagination.page == 1 }" @click="pagination.page --; listar();" href="#">Previous</a>
            </li>
            <li class="page-item"  >
              <a class="page-link" :class="{active:pagination.page == n}"  @click=" pagination.page = n; listar();" href="#" > {{n}} </a>
            </li>
            <li class="page-item">
              <a class="page-link" :class="{disabled :pagination.page == articles.last_page  }"  @click="pagination.page ++; listar();" href="#">Next</a>
            </li>
            <li class="page-item">
              <a class="page-link" :class="{disabled :pagination.page == articles.last_page  }" @click="pagination.page = articles.last_page; listar();" href="#">Last page</a>
            </li>
            </ul>
          </nav>


        </div>


      </div>


  </div>
</template>

<script>

export default {
  
    data(){
        return{
            //modal 0 es modal cerrado, 1 cuando se muestre.
            article: {
                name:'',
                description:'',
                stock: 1
            },
            id:0,
            modify:true,
            modal:0,
            titleModal:'',
            articles: [],
            errors: {},
            pagination: {
              page:1,
              per_page:3,
            },
           
        }

    },



    methods: {
      async listar(){
            const res = await axios.get('/articles', {params: this.pagination})
                 this.articles = res.data ;
        },

      async eliminar(id){
            const res = await axios.delete('/articles/'+id)
            //actualizo el array
            this.listar();
        },

       async guardar(){
        try {
            if(this.modify){
                const res = await axios.put('/articles/'+ this.id, this.article);
                this.cerrarModal();
                this.listar();


            }else{
                const res = await axios.post('/articles', this.article)
                //actualizo el array
                this.cerrarModal();
                this.listar();
            }
        } catch(e){

            if(e.response.data){
                this.errors = e.response.data.errors;
            }
           
        }
        

       }, 


        abrirModal(data={}){

            this.modal = 1;
            if(this.modify){

                this.id = data.id;
                this.titleModal ='Modify article';
                this.article.name = data.name;
                this.article.description = data.description;
                this.article.stock = data.stock;

                }else{

                    this.id = 0;
                    this.titleModal = 'Create article'
                    this.article.name = '';
                    this.article.description = '';
                    this.article.stock = 1;

                }

         },

        cerrarModal(){
            this.modal=0;
            this.errors={};
        }
        

      },

      mounted(){
        this.listar();
      },



}


</script>

<style>

.mostrar {
    display: list-item;
    opacity: 1;
    background: rgba(44, 38, 75, 0.849);
}

</style>