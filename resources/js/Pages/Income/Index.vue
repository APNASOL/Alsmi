<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Income Types</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Alsmi</a>
                        </li>
                        <li class="breadcrumb-item">Income Types</li>
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
                    <i class="bi bi-plus-lg"></i> New Income Types
                </button> -->
                <IncomeExpanseCreateComponent :process="Income" />
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title theme-text-color">
                        All Income Types
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
                                    v-for="(income, index) in IncomeTYpes"
                                    :key="income.id"
                                >
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>{{ income.name }}</td>
                                     
                                  
                                     
                                    <td>
                                        <div class="btn-group">
                                            <!-- <button
                                                class="btn btn-sm fs-6"
                                                title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateRecordModal"
                                                @click="
                                                    showEntry(income.id);
                                                    clearFields();
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button> -->
                                            <IncomeExpanseCreateComponent :process="Income" :id="income.id" />
                                            <DeleteModal
                                                :deleteId="income.id"
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
            IncomeTYpes: [],
            Income:'Income',
              
        };
    },
    created() {
        this.fetchIncomes();
    },
    methods: {
        fetchIncomes() {
            axios
                .get(route("api.income.expanse.fetch",this.Income))
                .then((response) => {
                   
                    this.IncomeTYpes = response.data;
                    console.log(response.data);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        
       
        deleteThis(id) {
            axios
                .delete(route("api.income.expanse.delete", [id,this.Income]))
                .then(() => {
                    this.fetchIncomes();
                    toastr.success("Income Types deleted successfully.");
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
