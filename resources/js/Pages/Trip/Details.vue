<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1 class="theme-text-color">{{ student.name }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">R & R Coal</a>
                        </li>
                        <li class="breadcrumb-item">Student</li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a :href="route('dashboard')" class="btn btn-success mt-3">
                    <i class="bi bi-back"></i> All Students
                </a>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card c-form-card">
                    <div class="card-header h5 theme-text-color">
                        {{ "Details" }}
                    </div>
                    
                    <div class="d-flex justify-content-end p-3">
                        <button class="btn">
                            <i
                                class="bi bi-pencil"
                                data-bs-toggle="modal"
                                data-bs-target="#studentmodal"
                                @click="editShow(studentId)"
                            ></i>
                        </button>
                        <DeleteModal
                            :deleteId="studentId"
                            @deleteThis="deleteThis"
                        />
                    </div>
                    <div class="col-md-12 p-2">
                        <div class="table-reponsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="col">
                                            {{ "Name" }}
                                        </th>
                                        <td scope="col">
                                            {{ student.name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            {{ "Father" }}
                                        </th>
                                        <td scope="col">
                                            {{ student.father_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            {{ "Gender" }}
                                        </th>
                                        <td scope="col">
                                            {{ student.gender }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            {{ "Date of Birth" }}
                                        </th>
                                        <td scope="col">
                                            {{ student.dob }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            {{ "Program" }}
                                        </th>
                                        <td scope="col">
                                            {{ student.programName }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            {{ "Address" }}
                                        </th>
                                        <td scope="col">
                                            {{ student.address }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col">
                                            {{ "Profile Picture" }}
                                        </th>
                                        <td scope="col">
                                            <!-- {{ student.profile_picture }} -->
                                            <ImageZooming
                                            :file="student.profile_picture"
                                            :width="200"
                                        />
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- student modal -->
            <div
                class="modal fade"
                id="studentmodal"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="section-title mt-1">
                                <h5 class="c-theme-red">
                                    Students Registraton form
                                </h5>
                            </div>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <div class="modal-body">
                             
                            <!-- Vertical Form -->
                            <div class="card card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="inputNanme4"
                                            class="form-label"
                                            >Name</label
                                        >
                                        <input
                                            type="name"
                                            class="form-control"
                                            id="name"
                                            :class="{
                                                'invalid-bg': formErrors.name,
                                            }"
                                            v-model="form.name"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.name"
                                        >
                                            {{ formErrors.name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label
                                            for="inputNanme4"
                                            class="form-label"
                                            >Father</label
                                        >
                                        <input
                                            type="father_name"
                                            class="form-control"
                                            id="father_name"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.father_name,
                                            }"
                                            v-model="form.father_name"
                                        />

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.father_name"
                                        >
                                            {{ formErrors.father_name[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label>
                                            {{ "Date of Birth" }}
                                        </label>
                                        <Datepicker
                                            autoApply
                                            :enable-time-picker="false"
                                            :class="{
                                                'invalid-bg': formErrors.dob,
                                            }"
                                            v-model="form.dob"
                                        >
                                        </Datepicker>
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.dob"
                                        >
                                            {{ formErrors.dob[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label>{{ "Gender" }}</label>
                                        <Multiselect
                                            v-model="form.gender"
                                            :options="genderOptions"
                                            :placeholder="'Gender'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg': formErrors.gender,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.gender"
                                        >
                                            {{ formErrors.gender[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label>{{ "Program" }}</label>
                                        <Multiselect
                                            v-model="form.program"
                                            :options="programOptions"
                                            :placeholder="'Program'"
                                            :searchable="true"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.program,
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.program"
                                        >
                                            {{ formErrors.program[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label
                                            for="inputAddress"
                                            class="form-label"
                                            >Address</label
                                        >
                                        <textarea
                                            type="address"
                                            class="form-control"
                                            id="address"
                                            :class="{
                                                'invalid-bg':
                                                    formErrors.address,
                                            }"
                                            v-model="form.address"
                                        ></textarea>

                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.address"
                                        >
                                            {{ formErrors.address[0] }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 mt-3">
                                        <div class="c-files">
                                            <span
                                                class="col-6 col-form-label mt-1"
                                                >{{ "Image" }}</span
                                            >
                                            <br />
                                            <CropperOffCanvas
                                                @croppedImg="croppedImgSubmit"
                                            />
                                             
                                        </div>
                                        <div
                                            class="invalid-feedback animated fadeIn"
                                            v-if="formErrors.image"
                                        >
                                            {{ formErrors.image[0] }}
                                        </div>
                                    </div>
                                    <div
                                        class="col-12 col-md-6 mt-3 text-center"
                                        v-if="existing_profil_picture"
                                    >
                                        {{ ("Profile Picture") }}
                                        <div
                                            class="col-6 d-flex justify-content-end"
                                        ></div>
                                        <img
                                            v-if="form.profile_picture"
                                            :src="form.profile_picture"
                                            width="200"
                                            @error="setAltImg"/>

                                        <img
                                        v-else
                                            :src="existing_profil_picture"
                                            width="300"
                                            @error="setAltImg"
                                        />
                                        
                                    </div>
                                    <div class="mt-5">
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            v-if="formStatus == 1"
                                            @click="submit"
                                        >
                                            Save
                                        </button>
                                        <button
                                            class="btn btn-success"
                                            type="button"
                                            disabled
                                            v-else
                                        >
                                            Saving
                                            <span
                                                class="spinner-border spinner-border-sm"
                                                role="status"
                                                aria-hidden="true"
                                            ></span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Vertical Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <button
                    hidden="hidden"
                    data-bs-toggle="modal"
                    data-bs-target="#studentmodal"
                    title="Delete"
                    ref="closeModal"
                ></button>
            </div>
        </section>
    </main>
</template>

<script>
import Master from "../Layout/Master.vue";
import axios from "axios";
import Multiselect from "@vueform/multiselect";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
export default {
    layout: Master,
    props: ["studentId"],
    components: {
        Multiselect,
        Datepicker,
    },
    created() {
        this.editShow(this.studentId);
        this.pluckRequiredData();
    },
    data() {
        return {
            student: [],

            form: {
                name: "",
                father_name: "",
                dob: "",
                gender: "",
                address: "",
                program: "",
            },
            formErrors: [],
            formStatus: 1,
            student_id: "",
            genderOptions: ["Male", "Female", "Other"],
            programOptions: [],
            existing_profil_picture:'',
        };
    },
    methods: {
        pluckRequiredData() {
            axios
                .get(route("api.student.required.models.pluck"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.programOptions = response.data.program;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        editShow(id) {
            axios
                .get(route("api.student.show", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.student = response.data;
                    this.student_id = response.data.id;
                    this.form.name = response.data.name;
                    this.form.father_name = response.data.father_name;
                    this.form.gender = response.data.gender;
                    this.form.dob = response.data.dob;
                    this.form.address = response.data.address;
                    this.form.program = response.data.program_id;
                    this.existing_profil_picture =  response.data.profile_picture;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },

        submit() {
            this.formStatus = 0;
            let formData = new FormData();

            formData.append("student_id", this.student_id); //this id for updating the record.
            formData.append(
                "profile_picture",
                this.form.profile_picture == "" ||
                    this.form.profile_picture == null
                    ? ""
                    : this.form.profile_picture
            );
            formData.append(
                "name",
                this.form.name == "" || this.form.name == null
                    ? ""
                    : this.form.name
            );
            formData.append(
                "father_name",
                this.form.father_name == "" || this.form.father_name == null
                    ? ""
                    : this.form.father_name
            );
            if (this.form.dob) {
                const startDateObject = new Date(this.form.dob);
                let dob = startDateObject.toLocaleString("en-US");
                formData.append("dob", dob ?? "");
            }
            formData.append(
                "gender",
                this.form.gender == "" || this.form.gender == null
                    ? ""
                    : this.form.gender
            );
            formData.append(
                "program",
                this.form.program == "" || this.form.program == null
                    ? ""
                    : this.form.program
            );
            formData.append(
                "address",
                this.form.address == "" || this.form.address == null
                    ? ""
                    : this.form.address
            );

            let url = "";

            axios
                .post(route("api.student.store"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.formStatus = 1;
                    toastr.success("Student record saved successfully");
                    this.editShow(this.studentId);
                    this.$refs.closeModal.click();
                    this.student_id = "";
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    this.formErrors = error.response.data.errors;
                });
        },
        croppedImgSubmit(img) {
            this.form.profile_picture = img;
        },
        deleteThis(id) {
            axios
                .delete(route("api.student.delete", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    toastr.success("Student record deleted successfully");
                    this.$inertia.get(route("students"));
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        setAltImg(event) {
            event.target.src = "/images/default.jpg";
        },
    },
};
</script>

<style lang="scss" scoped></style>
