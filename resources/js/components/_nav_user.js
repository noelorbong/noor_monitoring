export default {
    items: [
        {
            name: "Dashboard",
            url: "/user/dashboard",
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
        // {
        //     name: "Tribe",
        //     url: "/user/tribe",
        //     icon: "fa fa-fort-awesome",
        // },
        {
            name: "Members",
            url: "/user",
            icon: "fa fa-users",
            children: [
                {
                    name: "List",
                    url: "/user/member/list",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/user/member/create",
                    icon: "fa fa-plus"
                }
            ]
        },
        {
            name: "Tribe Activities",
            url: "/tribeactivity",
            icon: "fa fa-tasks",
            children: [
                {
                    name: "List",
                    url: "/user/tribeactivity/main",
                    icon: "fa fa-list"
                },
                {
                    name: "Add",
                    url: "/user/tribeactivity/main/create",
                    icon: "fa fa-plus"
                }
            ]
        },
        {
            name: "Church Activities",
            url: "/user/activity/main",
            icon: "fa fa-tasks",
        },
        {
            name: "Progress",
            url: "/user/progress",
            icon: "fa fa-level-up"
        },
        {
            name: "Life Class",
            url: "/user/lifeclass",
            icon: "fa fa-graduation-cap",
        }
 
    ]
}
