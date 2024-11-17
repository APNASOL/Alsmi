<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">Enrollement</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Admissions</li>
                        <li class="breadcrumb-item active">Enrollement</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button
                    class="btn btn-success mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#studentmodal"
                >
                    <i class="bi bi-plus-lg"></i> New Enrollment
                </button>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    
                        <div class="row p-3">
                            <h5 class="card-title theme-text-color">
                               All Enrollement
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Father</th>
                                            <th>Batch</th>
                                            <th>Session</th>
                                            <th>Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                        <tr>
                                            <td>Ali Khan</td>
                                            <td>Gul Ahmad</td>
                                            <td>2023-2025</td>
                                            <td>Fall</td>
                                            <td>4th</td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                   
                     
                </div>
            </div>
        </section>
    </main>
</template>

<script>
import Master from "../Layout/Master.vue";
import Multiselect from "@vueform/multiselect";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import axios from "axios";
export default {
    layout: Master,
    props: [],
    components: {
        Multiselect,
        Datepicker,
    },
  
    created() {
        // this.function();
    },
    setup() {
        return {};
    },
    methods: {
        // function() {
        //     axios
        //         .get(route("api.abc", id), {
        //             headers: {
        //                 Authorization: "Bearer " + this.$page.props.auth_token,
        //             },
        //         })
        //         .then((response) => {
        //             this.ABC = response.data;
        //         })
        //         .catch((error) => {
        //             toastr.error(error.response.data.message);
        //         });
        // },
    },
};
</script>

<style lang="scss" scoped></style>
