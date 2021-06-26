export default {
    items: [
        {
            name: "Dashboard",
            url: "/main/dashboard",
            icon: "icon-speedometer",
            badge: {
                variant: "primary",
                // text: "NEW"
            }
        },
        {
            title: true,
            name: "Data Manager"
        },
        {
            name: "Members",
            url: "/user",
            icon: "fa fa-users",
            children: [
                {
                    name: "List",
                    url: "/main/member/list",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/main/member/create",
                    icon: "fa fa-plus"
                }
            ]
        },
        {
            name: "Activities",
            url: "/activity",
            icon: "fa fa-tasks",
            children: [
                {
                    name: "List",
                    url: "/main/activity/main",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/main/activity/main/create",
                    icon: "fa fa-plus"
                }
            ]
        },
        {
            name: "Tribes",
            url: "/tribe",
            icon: "fa fa-fort-awesome",
            children: [
                {
                    name: "List",
                    url: "/main/tribe",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/main/tribe/create",
                    icon: "fa fa-plus"
                }
            ]
        },
        {
            name: "Categories",
            url: "/category",
            icon: "fa fa-tag",
            children: [
                {
                    name: "List",
                    url: "/main/category",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/main/category/create",
                    icon: "fa fa-plus"
                }
            ]
        },
        {
            name: "SpecializedMinistry",
            url: "/specializedministry",
            icon: "fa fa-university",
            children: [
                {
                    name: "List",
                    url: "/main/specializedministry",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/main/specializedministry/create",
                    icon: "fa fa-plus"
                }
            ]
        },
        {
            name: "Progress",
            url: "/progress",
            icon: "fa fa-level-up",
            children: [
                {
                    name: "List",
                    url: "/main/progress",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/main/progress/create",
                    icon: "fa fa-plus"
                }

            ]
        },
        {

            name: "Life Class",
            url: "/lifeclass",
            icon: "fa fa-graduation-cap",
            children: [
                {
                    name: "List",
                    url: "/main/lifeclass",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/main/lifeclass/create",
                    icon: "fa fa-plus"
                }

            ]
        }
        
    ]
}
