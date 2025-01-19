<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Expanse Types</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Expanse Types</li>
                        <li class="breadcrumb-item active">Index</li>
                    </ol>
                </nav>
            </div>
            <div>
                <!-- <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#updateRecordModal"
                    @click="clearFields"
                >
                    <i class="bi bi-plus-lg"></i> New Expanse Types
                </button> -->
                <IncomeExpanseCreateComponent :process="Expanse" />
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">
                        All Expanse Types
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                     
                              
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(expanse, index) in ExpanseTypes"
                                    :key="expanse.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ expanse.name }}</td>
                                     
                                  
                                     
                                    <td>
                                        <div class="btn-group">
                                            <IncomeExpanseCreateComponent :process="Expanse" :id="expanse.id" />

                                            
                                            <DeleteModal
                                                :deleteId="expanse.id"
                                                @deleteThis="deleteThis"
                                            ></DeleteModal>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           
        </section>
    </main>
</template>

<script>
import axios from "axios";
import Master from "../Layout/Master.vue";

export default {
    layout: Master,
    data() {
        return {
            ExpanseTypes: [],
            Expanse:'Expanse',
              
        };
    },
    created() {
        this.fetchExpanseTypes();
    },
    methods: {
        fetchExpanseTypes() {
            axios
                .get(route("api.income.expanse.fetch",this.Expanse))
                .then((response) => {
                   
                    this.ExpanseTypes = response.data;
                    console.log(response.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        
       
        deleteThis(id) {
            axios
                .delete(route("api.transaction.delete", id))
                .then(() => {
                    this.fetchExpanseTypes();
                    toastr.success("Expanse Types deleted successfully.");
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>

<style scoped>
.invalid-bg {
    border-color: #f8d4d4;
    background-color: #f8d4d4;
}
.invalid-feedback {
    color: red;
    font-size: 0.875rem;
}
</style>
