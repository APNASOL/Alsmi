<template>
    <div class="main">
        <header id="header" class="header fixed-top d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <a
                    :href="route('dashboard')"
                    class="logo d-flex align-items-center"
                >
                    <img
                        src="http://alsmi.test/backend/assets/img/logo.png"
                        alt=""
                    />
                    <span class="d-none d-lg-block theme-text-color"
                        >Alsmi</span
                    >
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div>

            <!-- End Search Bar -->

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">
                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle" href="#">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <!-- End Search Icon-->

                    <li class="nav-item dropdown pe-3">
                        <a
                            class="nav-link nav-profile d-flex align-items-center pe-0"
                            href="#"
                            data-bs-toggle="dropdown"
                        >
                            <img
                                src="http://alsmi.test/backend/assets/img/profile-img.jpg"
                                alt="Profile"
                                class="rounded-circle"
                            />
                            <span class="d-none d-md-block dropdown-toggle ps-2"
                                >K. Anderson</span
                            > </a
                        ><!-- End Profile Iamge Icon -->

                        <ul
                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
                        >
                            <li class="dropdown-header">
                                <h6>Kevin Anderson</h6>
                                <span>Account Officer</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>

                            <li>
                                <hr class="dropdown-divider" />
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="users-profile.html"
                                >
                                    <i class="bi bi-gear"></i>
                                    <span>Account Settings</span>
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider" />
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    href="javascript:;"
                                    @click="logout"
                                >
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </a>
                            </li>
                        </ul>
                        <!-- End Profile Dropdown Items -->
                    </li>
                    <!-- End Profile Nav -->
                </ul>
            </nav>
            <!-- End Icons Navigation -->
        </header>
        <!-- End Header -->

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">
            <ul class="sidebar-nav" id="sidebar-nav">
                <!-- Dashboard Nav Item -->
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :class="{ active: isActive('/dashboard') }"
                        href="/dashboard"
                    >
                        <i class="bi bi-house-door"></i>
                        <!-- Icon for Dashboard -->
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- Transactions Nav Item -->
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :class="{ active: isActive('/transactions') }"
                        href="/transactions"
                    >
                        <i class="bi bi-cash"></i>
                        <!-- Icon for Transactions -->
                        <span>Transactions</span>
                    </a>
                </li>

                <!-- Income Statements Nav Item -->
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :class="{ active: isActive('/income-statements') }"
                        href="/income-statements"
                    >
                        <i class="bi bi-file-earmark-text"></i>
                        <!-- Icon for Income Statements -->
                        <span>Income Statements</span>
                    </a>
                </li>
                <!-- Reports Nav Item -->
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :class="{ active: isActive('/reports') }"
                        href="/reports"
                    >
                        <i class="bi bi-bar-chart-line"></i>
                        <!-- Icon for Reports -->
                        <span>Reports</span>
                    </a>
                </li>
                <!-- Users Nav Item -->
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :class="{ active: isActive('/expense/types') }"
                        href="/expense/types"
                    >
                        <i class="bi bi-person-circle"></i>
                        <!-- Icon for Users -->
                        <span>Expense Types</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :class="{ active: isActive('/income/types') }"
                        href="/income/types"
                    >
                        <i class="bi bi-person-circle"></i>
                        <!-- Icon for Users -->
                        <span>Income Types</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :class="{ active: isActive('/users') }"
                        href="/users"
                    >
                        <i class="bi bi-person-circle"></i>
                        <!-- Icon for Users -->
                        <span>Software Users</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- End Sidebar-->

        <slot></slot>

        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>Alsmi</span></strong
                >. All Rights Reserved
            </div>
            <div class="credits">
                Designed by
                <a href="https://goritmi.co.uk">Goritim Ltd</a>
            </div>
        </footer>
        <!-- End Footer -->
    </div>
</template>

<script>
import "http://alsmi.test/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js";
import axios from "axios";

export default {
    mounted() {
        let mainJs = document.createElement("script");
        mainJs.setAttribute(
            "src",
            "http://alsmi.test/backend/assets/js/main.js"
        );

        document.head.appendChild(mainJs);
    },

    setup() {
        return {};
    },
    methods: {
        isActive(route) {
            return window.location.pathname === route;
        },
        logout() {
            axios
                .post(route("api.logout"), this.form, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    this.$inertia.get(route("login"));
                })
                .catch((error) => {});
        },
    },
};
</script>

<style>
@import url("public/backend/assets/vendor/bootstrap/css/bootstrap.min.css");
@import url("public/backend/assets/vendor/bootstrap-icons/bootstrap-icons.css");
@import url("public/backend/assets/vendor/boxicons/css/boxicons.min.css");
@import url("public/backend/assets/vendor/remixicon/remixicon.css");
@import url("public/backend/assets/vendor/simple-datatables/style.css");
@import url("public/backend/assets/css/style.css");
@import url("public/backend/assets/css/custom.css");

@import "toastr";
</style>

<style>
@import "@vueform/multiselect/themes/default.css";
/* custom style that should be moved into the custom.css file later */
.section-title {
    text-align: left;
    padding-bottom: 5px;
}
.nav-link.active {
    background-color: #f6f9ff !important; /* Change this to your desired color */
    color: #174696 !important;
}
.section-title h5 {
    font-size: 22px;
    font-weight: bold;
    text-transform: uppercase;
    position: relative;
    color: rgb(13, 32, 201);
}
/* .section-title h5::before {
    content: "";
    position: absolute;
    display: block;
    width: 120px;
    height: 1px;
    background: #ddd;
    bottom: 1px;
    left: calc(50% - 60px);
}
.section-title h5::after {
    content: "";
    position: absolute;
    display: block;
    width: 40px;
    height: 3px;
    background: rgb(13, 32, 201);
    bottom: 0;
    left: calc(50% - 20px);
} */
.c-file-padding {
    padding: 1rem 0.75rem !important;
}
.invalid-feedback {
    display: block !important;
}
.invalid-bg {
    border-color: #f8d4d4 !important;
    background-color: #f8d4d4 !important;
}

.btn-success {
    background-color: rgb(65, 84, 241) !important;
    border-color: rgb(65, 84, 241) !important;
}

.btn-success:hover {
    background-color: rgb(13, 32, 201) !important;
    border-color: rgb(13, 32, 201) !important;
}

.btn-success:active {
    background-color: rgb(13, 32, 201) !important;
    border-color: rgb(13, 32, 201) !important;
}

.btn-success:focus {
    color: #fff !important;
    box-shadow: none !important;
}
.toast-success {
    background-color: rgb(65, 84, 241) !important;
}
.theme-text-color {
    color: rgb(13, 32, 201) !important;
    font-weight: bold;
}
.theme-bg-color {
    background-color: rgb(13, 32, 201) !important;
}
.text-primary {
    color: rgb(13, 32, 201) !important;
}
</style>
