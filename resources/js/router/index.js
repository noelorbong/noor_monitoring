import Vue from "vue"
import Router from "vue-router"

//Auth
import Login from "../components/auth/Login.vue"
import Register from "../components/auth/Register.vue"
import Page403 from "../components/auth/Page403.vue"

import Welcome from "../components/Welcome.vue"
// Containers

//Admin
import AdminFull from "../components/layouts/AppLayoutAdmin"
import Dashboard from "../components/pages/admin/Dashboard.vue"

import MemberCreate from "../components/pages/admin/member/MemberCreate.vue"
import MemberList from "../components/pages/admin/member/MemberList.vue"
import MemberProfile from "../components/pages/admin/member/MemberProfile.vue"
import MemberProfileEdit from "../components/pages/admin/member/MemberEdit.vue"

import ActivityCreate from "../components/pages/admin/activity/Create.vue"
import ActivityList from "../components/pages/admin/activity/List.vue"
import ActivityProfile from "../components/pages/admin/activity/Profile.vue"
import ActivityProfileEdit from "../components/pages/admin/activity/Edit.vue"

import ActivityMainCreate from "../components/pages/admin/mainactivity/Create.vue"
import ActivityMainEdit from "../components/pages/admin/mainactivity/Edit.vue"
import ActivityMainList from "../components/pages/admin/mainactivity/List.vue"

import TribeCreate from "../components/pages/admin/tribe/Create.vue"
import TribeEdit from "../components/pages/admin/tribe/Edit.vue"
import TribeList from "../components/pages/admin/tribe/List.vue"
import TribeProfile from "../components/pages/admin/tribe/Profile.vue"

import CategoryCreate from "../components/pages/admin/category/Create.vue"
import CategoryEdit from "../components/pages/admin/category/Edit.vue"
import CategoryList from "../components/pages/admin/category/List.vue"
import CategoryProfile from "../components/pages/admin/category/Profile.vue"
//admin end

import UserFull from "../components/layouts/AppLayoutUser"
import UserDashboard from "../components/pages/user/Dashboard.vue"

Vue.use(Router)

export default new Router({
    mode: "hash", // Demo is living in GitHub.io, so required!
    linkActiveClass: "open active",
    scrollBehavior: () => ({
        y: 0
    }),
    routes: [
        {
            path: "/",
            redirect: "/welcome",
            name: "Pages",
            component: {
                render(c) {
                    return c("router-view")
                }
            },
            children: [
                {
                    path: "/welcome",
                    name: "Welcome",
                    component: Welcome,
                    meta: {
                        auth: undefined
                    }
                },
                {
                    path: "/login",
                    name: "Login",
                    component: Login,
                    meta: {
                        auth: false
                    }
                },
                {
                    path: "/register",
                    name: "Register",
                    component: Register,
                    meta: {
                        auth: false
                    }
                },
                {
                    path: "/page403",
                    name: "Page403",
                    component: Page403,
                    meta: {
                        auth: undefined
                    }
                }
            ]
        },
        {
            path: "/main",
            redirect: "/main/dashboard",
            name: "Main",
            component: AdminFull,
            children: [
                {
                    path: "dashboard",
                    name: "Dashboard",
                    component: Dashboard,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "member/list",
                    name: "MemberList",
                    component: MemberList,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "member/create",
                    name: "MemberCreate",
                    component: MemberCreate,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "member/profile/:id",
                    name: "MemberProfile",
                    component: MemberProfile,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "member/profile/edit/:id",
                    name: "MemberProfileEdit",
                    component: MemberProfileEdit,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "activity/main",
                    name: "ActivityMainList",
                    component: ActivityMainList,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "activity/main/create",
                    name: "ActivityMainCreate",
                    component: ActivityMainCreate,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "activity/main/edit/:id",
                    name: "ActivityMainEdit",
                    component: ActivityMainEdit,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "activity/list/:id",
                    name: "ActivityList",
                    component: ActivityList,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "activity/create/:id",
                    name: "ActivityCreate",
                    component: ActivityCreate,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "activity/profile/:id",
                    name: "ActivityProfile",
                    component: ActivityProfile,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "activity/profile/edit/:id",
                    name: "ActivityProfileEdit",
                    component: ActivityProfileEdit,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "tribe",
                    name: "TribeList",
                    component: TribeList,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "tribe/create",
                    name: "TribeCreate",
                    component: TribeCreate,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "tribe/edit/:id",
                    name: "TribeEdit",
                    component: TribeEdit,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "tribe/profile/:id",
                    name: "TribeProfile",
                    component: TribeProfile,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "category",
                    name: "CategoryList",
                    component: CategoryList,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "category/create",
                    name: "CategoryCreate",
                    component: CategoryCreate,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "category/edit/:id",
                    name: "CategoryEdit",
                    component: CategoryEdit,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                },
                {
                    path: "category/profile/:id",
                    name: "CategoryProfile",
                    component: CategoryProfile,
                    meta: {
                        auth: {
                            roles: 2,
                            redirect: { name: "login" },
                            forbiddenRedirect: "/Page403"
                        }
                    }
                }
            ]
        },
        {
            path: "/user",
            redirect: "/user/dashboard",
            name: "User",
            component: UserFull,
            children: [
                {
                    path: "dashboard",
                    name: "UserDashboard",
                    component: UserDashboard,
                    meta: {
                        auth: true
                    }
                }
            ]
        }
    ]
})
