import Vue from "vue"
import Router from "vue-router"

// Auth
import Login from "../components/auth/Login.vue"
import Register from "../components/auth/Register.vue"
import Page403 from "../components/auth/Page403.vue"

import Welcome from "../components/Welcome.vue"
// Containers

// Admin
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

import SpecializedMinistryCreate from "../components/pages/admin/specializedministry/Create.vue"
import SpecializedMinistryEdit from "../components/pages/admin/specializedministry/Edit.vue"
import SpecializedMinistryList from "../components/pages/admin/specializedministry/List.vue"
import SpecializedMinistryProfile from "../components/pages/admin/specializedministry/Profile.vue"

import ProgressCreate from "../components/pages/admin/progress/Create.vue"
import ProgressEdit from "../components/pages/admin/progress/Edit.vue"
import ProgressList from "../components/pages/admin/progress/List.vue"
import ProgressProfile from "../components/pages/admin/progress/Profile.vue"

import LifeclassCreate from "../components/pages/admin/lifeclass/Create.vue"
import LifeclassEdit from "../components/pages/admin/lifeclass/Edit.vue"
import LifeclassList from "../components/pages/admin/lifeclass/List.vue"
import LifeclassProfile from "../components/pages/admin/lifeclass/Profile.vue"

// Admin End

// User

import UserMemberList from "../components/pages/user/member/MemberList.vue"
import UserMemberProfile from "../components/pages/user/member/MemberProfile.vue"
import UserMemberCreate from "../components/pages/user/member/MemberCreate.vue"
import UserMemberProfileEdit from "../components/pages/user/member/MemberEdit.vue"

import UserProgressCreate from "../components/pages/user/progress/ProgressCreate.vue"
import UserProgressEdit from "../components/pages/user/progress/ProgressEdit.vue"
import UserProgressList from "../components/pages/user/progress/ProgressList.vue"
import UserProgressProfile from "../components/pages/user/progress/ProgressProfile.vue"

import UserLifeclassCreate from "../components/pages/user/lifeclass/LifeclassCreate.vue"
import UserLifeclassEdit from "../components/pages/user/lifeclass/LifeclassEdit.vue"
import UserLifeclassList from "../components/pages/user/lifeclass/LifeclassList.vue"
import UserLifeclassProfile from "../components/pages/user/lifeclass/LifeclassProfile.vue"

import UserActivityCreate from "../components/pages/user/activity/ActivityCreate.vue"
import UserActivityList from "../components/pages/user/activity/ActivityList.vue"
import UserActivityProfile from "../components/pages/user/activity/ActivityProfile.vue"
import UserActivityProfileEdit from "../components/pages/user/activity/ActivityEdit.vue"

import UserActivityMainCreate from "../components/pages/user/mainactivity/MainactivityCreate.vue"
import UserActivityMainEdit from "../components/pages/user/mainactivity/MainactivityEdit.vue"
import UserActivityMainList from "../components/pages/user/mainactivity/MainactivityList.vue"

// import UserTribe from "../components/pages/user/tribe/tribe.vue"

import TribeactivityCreate from "../components/pages/user/tribeactivity/tribeactivityCreate.vue"
import TribeactivityList from "../components/pages/user/tribeactivity/tribeactivityList.vue"
import TribeactivityProfile from "../components/pages/user/tribeactivity/tribeactivityProfile.vue"
import TribeactivityProfileEdit from "../components/pages/user/tribeactivity/tribeactivityEdit.vue"

import TribeactivityMainCreate from "../components/pages/user/maintribeactivity/MaintribeactivityCreate.vue"
import TribeactivityMainEdit from "../components/pages/user/maintribeactivity/MaintribeactivityEdit.vue"
import TribeactivityMainList from "../components/pages/user/maintribeactivity/MaintribeactivityList.vue"

// User End

import UserFull from "../components/layouts/AppLayoutUser"
import UserDashboard from "../components/pages/user/Dashboard.vue"

Vue.use(Router)

export default new Router({
  mode: "hash", // Demo is living in GitHub.io, so required!
  linkActiveClass: "open active",
  scrollBehavior: () => ({
    y: 0,
  }),
  routes: [
    {
      path: "/",
      redirect: "/welcome",
      name: "Pages",
      component: {
        render(c) {
          return c("router-view")
        },
      },
      children: [
        {
          path: "/welcome",
          name: "Welcome",
          component: Welcome,
          meta: {
            auth: undefined,
          },
        },
        {
          path: "/login",
          name: "login",
          component: Login,
          meta: {
            auth: undefined,
          },
        },
        {
          path: "/register",
          name: "Register",
          component: Register,
          meta: {
            auth: undefined,
          },
        },
        {
          path: "/page403",
          name: "Page403",
          component: Page403,
          meta: {
            auth: undefined,
          },
        },
      ],
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
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "member/list",
          name: "MemberList",
          component: MemberList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "member/create",
          name: "MemberCreate",
          component: MemberCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "member/profile/:id",
          name: "MemberProfile",
          component: MemberProfile,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "member/profile/edit/:id",
          name: "MemberProfileEdit",
          component: MemberProfileEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "activity/main",
          name: "ActivityMainList",
          component: ActivityMainList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "activity/main/create",
          name: "ActivityMainCreate",
          component: ActivityMainCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "activity/main/edit/:id",
          name: "ActivityMainEdit",
          component: ActivityMainEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "activity/list/:id",
          name: "ActivityList",
          component: ActivityList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "activity/create/:id",
          name: "ActivityCreate",
          component: ActivityCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "activity/profile/:id",
          name: "ActivityProfile",
          component: ActivityProfile,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "activity/profile/edit/:id",
          name: "ActivityProfileEdit",
          component: ActivityProfileEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "tribe",
          name: "TribeList",
          component: TribeList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "tribe/create",
          name: "TribeCreate",
          component: TribeCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "tribe/edit/:id",
          name: "TribeEdit",
          component: TribeEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "tribe/profile/:id",
          name: "TribeProfile",
          component: TribeProfile,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "category",
          name: "CategoryList",
          component: CategoryList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "category/create",
          name: "CategoryCreate",
          component: CategoryCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "category/edit/:id",
          name: "CategoryEdit",
          component: CategoryEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "category/profile/:id",
          name: "CategoryProfile",
          component: CategoryProfile,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "specializedministry",
          name: "SpecializedMinistryList",
          component: SpecializedMinistryList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "specializedministry/create",
          name: "SpecializedMinistryCreate",
          component: SpecializedMinistryCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "specializedministry/edit/:id",
          name: "SpecializedMinistryEdit",
          component: SpecializedMinistryEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "specializedministry/profile/:id",
          name: "SpecializedMinistryProfile",
          component: SpecializedMinistryProfile,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "progress",
          name: "ProgressList",
          component: ProgressList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "progress/create",
          name: "ProgressCreate",
          component: ProgressCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "progress/edit/:id",
          name: "ProgressEdit",
          component: ProgressEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "progress/profile/:id",
          name: "ProgressProfile",
          component: ProgressProfile,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "lifeclass",
          name: "LifeclassList",
          component: LifeclassList,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "lifeclass/create",
          name: "LifeclassCreate",
          component: LifeclassCreate,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "lifeclass/edit/:id",
          name: "LifeclassEdit",
          component: LifeclassEdit,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
        {
          path: "lifeclass/profile/:id",
          name: "LifeclassProfile",
          component: LifeclassProfile,
          meta: {
            auth: {
              roles: 2,
              redirect: { name: "login" },
              forbiddenRedirect: "/Page403",
            },
          },
        },
      ],
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
            auth: true,
          },
        },
        {
          path: "member/list",
          name: "UserMemberList",
          component: UserMemberList,
          meta: {
            auth: true,
          },
        },
        {
          path: "member/profile/:id",
          name: "UserMemberProfile",
          component: UserMemberProfile,
          meta: {
            auth: true,
          },
        },
        {
          path: "member/create",
          name: "UserMemberCreate",
          component: UserMemberCreate,
          meta: {
            auth: true,
          },
        },
        {
          path: "member/profile/edit/:id",
          name: "UserMemberProfileEdit",
          component: UserMemberProfileEdit,
          meta: {
            auth: true,
          },
        },
        {
          path: "progress",
          name: "UserProgressList",
          component: UserProgressList,
          meta: {
            auth: true,
          },
        },
        {
          path: "progress/create",
          name: "UserProgressCreate",
          component: UserProgressCreate,
          meta: {
            auth: true,
          },
        },
        {
          path: "progress/edit/:id",
          name: "UserProgressEdit",
          component: UserProgressEdit,
          meta: {
            auth: true,
          },
        },
        {
          path: "progress/profile/:id",
          name: "UserProgressProfile",
          component: UserProgressProfile,
          meta: {
            auth: true,
          },
        },
        {
          path: "lifeclass",
          name: "UserLifeclassList",
          component: UserLifeclassList,
          meta: {
            auth: true,
          },
        },
        {
          path: "lifeclass/create",
          name: "UserLifeclassCreate",
          component: UserLifeclassCreate,
          meta: {
            auth: true,
          },
        },
        {
          path: "lifeclass/edit/:id",
          name: "UserLifeclassEdit",
          component: UserLifeclassEdit,
          meta: {
            auth: true,
          },
        },
        {
          path: "lifeclass/profile/:id",
          name: "UserLifeclassProfile",
          component: UserLifeclassProfile,
          meta: {
            auth: true,
          },
        },
        {
          path: "activity/list/:id",
          name: "UserActivityList",
          component: UserActivityList,
          meta: {
            auth: true,
          },
        },
        {
          path: "activity/create/:id",
          name: "UserActivityCreate",
          component: UserActivityCreate,
          meta: {
            auth: true,
          },
        },
        {
          path: "activity/profile/:id",
          name: "UserActivityProfile",
          component: UserActivityProfile,
          meta: {
            auth: true,
          },
        },
        {
          path: "activity/profile/edit/:id",
          name: "UserActivityProfileEdit",
          component: UserActivityProfileEdit,
          meta: {
            auth: true,
          },
        },
        {
          path: "activity/main",
          name: "UserActivityMainList",
          component: UserActivityMainList,
          meta: {
            auth: true,
          },
        },
        {
          path: "activity/main/create",
          name: "UserActivityMainCreate",
          component: UserActivityMainCreate,
          meta: {
            auth: true,
          },
        },
        {
          path: "activity/main/edit/:id",
          name: "UserActivityMainEdit",
          component: UserActivityMainEdit,
          meta: {
            auth: true,
          },
        },
        // {
        //     path: "tribe",
        //     name: "UserTribe",
        //     component: UserTribe,
        //     meta: {
        //         auth: true
        //     }
        // },
        {
          path: "tribeactivity/main",
          name: "TribeactivityMainList",
          component: TribeactivityMainList,
          meta: {
            auth: true,
          },
        },
        {
          path: "tribeactivity/main/create",
          name: "TribeactivityMainCreate",
          component: TribeactivityMainCreate,
          meta: {
            auth: true,
          },
        },
        {
          path: "tribeactivity/main/edit/:id",
          name: "TribeactivityMainEdit",
          component: TribeactivityMainEdit,
          meta: {
            auth: true,
          },
        },
        {
          path: "tribeactivity/list/:id",
          name: "TribeactivityList",
          component: TribeactivityList,
          meta: {
            auth: true,
          },
        },
        {
          path: "tribeactivity/create/:id",
          name: "TribeactivityCreate",
          component: TribeactivityCreate,
          meta: {
            auth: true,
          },
        },
        {
          path: "tribeactivity/profile/:id",
          name: "TribeactivityProfile",
          component: TribeactivityProfile,
          meta: {
            auth: true,
          },
        },
        {
          path: "tribeactivity/profile/edit/:id",
          name: "TribeactivityProfileEdit",
          component: TribeactivityProfileEdit,
          meta: {
            auth: true,
          },
        },
      ],
    },
  ],
})
