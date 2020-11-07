////////////////////// ImplicitMSALAuthenticationProvider//////////////////////
// Configuration options for MSAL @see https://github.com/AzureAD/microsoft-authentication-library-for-js/wiki/MSAL.js-1.0.0-api-release#configuration-options

// clientId: "3b2efdf9-1c0b-4046-9fb8-f5e1555172ca", // Client Id of the registered application
// authority: "https://login.microsoftonline.com/fdc65df4-73ec-4a99-9dfe-61ebaf51de92", //tenant info


const msalConfig = {
    auth: {
        clientId: "3b2efdf9-1c0b-4046-9fb8-f5e1555172ca", // Client Id of the registered application
        authority: "https://login.microsoftonline.com/fdc65df4-73ec-4a99-9dfe-61ebaf51de92", //tenant info
        redirectUri: "https://ajcode.tk/teaching_platform_dev/teams_liveManagement",
    },
};
const graphScopes = ["user.read"]; // An array of graph scopes
// const graphScopes = [".default"]; // An array of graph scopes

// Important Note: This library implements loginPopup and acquireTokenPopup flow, remember this while initializing the msal
// Initialize the MSAL @see https://github.com/AzureAD/microsoft-authentication-library-for-js#1-instantiate-the-useragentapplication
const msalApplication = new Msal.UserAgentApplication(msalConfig);
const options = new MicrosoftGraph.MSALAuthenticationProviderOptions(graphScopes);
const authProvider = new MicrosoftGraph.ImplicitMSALAuthenticationProvider(msalApplication, options);

////////////////////// ImplicitMSALAuthenticationProvider //////////////////////



////////////////////// Microsoft Graph Client object //////////////////////
const MSGCO_options = {
    authProvider, // An instance created from previous step
};
const Client = MicrosoftGraph.Client;
const client = Client.initWithMiddleware(MSGCO_options);
////////////////////// Microsoft Graph Client object //////////////////////




////////////////////// Make requests to the graph //////////////////////
const jsonPre = document.getElementById("json");

async function API_me() {
    try {
        let userDetails = await client
            .api("/me")
            .version('beta')
            .get();
        jsonPre.innerHTML = JSON.stringify(userDetails, null, 2);
        console.log(JSON.stringify(userDetails, null, 2));
    } catch (error) {
        throw error;
    }
}

async function API_createOnlineMeeting() {
    const onlineMeeting = {
        startDateTime:"2020-02-19T05:50:00.9527918Z",
        endDateTime:"2020-02-19T06:00:00.9527918Z",
        subject:"第1次會議測試",
        participants: {
            "attendees": [
                    // {
                    //     "upn": "s17113210@stu.edu.tw",
                    // },
                    // {
                    //     "upn": "s17113210@stu.edu.tw",
                    // },
                    // {
                    //     "upn": "s17113209@stu.edu.tw",
                    // },
                    {
                        "upn": "qew0921715330@gmail.com",
                    }
                    // {
                    //     "upn": "a42157595@gmail.com",
                    // }
                ],
            // "organizer": {
            //     "upn": "s17113201@stu.edu.tw"
            // },
        },

    };

    try {
        let res = await client
            .api('/communications/onlineMeetings')
            .version('beta')
            .post(onlineMeeting);
        console.log(res);
        jsonPre.innerHTML = JSON.stringify(res, null, 2);
    } catch (e) {
        throw e;
    }
}

async function API_deleteOnlineMeeting() {
    try {
        let res = await client.api('/me/onlineMeetings/' + id).delete();
        console.log(res);
    }catch (e) {
        throw e
    }
}

async function API_outlookCreateOnlineMeeting() {
    const event = {
        subject: "會議測試",
        body: {
            contentType: "HTML",
            content: "會議測試"
        },
        start: {
            "dateTime": "2020-04-03T13:40:00.0000000",
            "timeZone": "Asia/Taipei"
        },
        end: {
            "dateTime": "2020-04-03T13:50:00.0000000",
            "timeZone": "Asia/Taipei"
        },
        location:{
            displayName:"L0732 Room"
        },
        attendees: [
            // {
            //     emailAddress: {
            //         address:"s17113201@stu.edu.tw",
            //         name: "黃晉緯"
            //     },
            //     type: "required"
            // },
			{
			    emailAddress: {
			        address:"qew0921715330@gmail.com",
			        name: "student"
			    },
			    type: "required"
			},
            // {
            //     emailAddress: {
            //         address:"a42157595@gmail.com",
            //         name: "學生",
            //     },s
            //     type: "required"
            // },
            // {
            //     emailAddress: {
            //         address:"s17113210@stu.edu.tw",
            //         name: "梁朝輝"
            //     },
            //     type: "required"
            // },
            // {
            //     emailAddress: {
            //         address:"s17113209@stu.edu.tw",
            //         name: "陳建智"
            //     },
            //     type: "required"
            // },
            // {  emailAddress: {
            //         address:"Labtest01@certitrain.onmicrosoft.com",
            //         name: "測試帳號"
            //     },
            //     type: "required"
            // },
        ],
        allowNewTimeProposals: true,
        isOnlineMeeting: true,
        onlineMeetingProvider: "teamsForBusiness",
    };

    try {
        let res = await client.api('/me/events')
            .version('beta')
            .post(event);
        console.log(res);
        jsonPre.innerHTML = JSON.stringify(res, null, 2);
    }catch (e) {
        throw e
    }
}

async function API_createEvent(){
    const event = {
        subject: "第1次事件測試",
        body: {
            contentType: "HTML",
            content: "Does late morning work for you?"
        },
        start: {
            dateTime: "2020-02-27T14:00:00",
            timeZone: "Asia/Taipei"
        },
        end: {
            dateTime: "2020-02-27T14:20:00",
            timeZone: "Asia/Taipei"
        },
        location:{
            displayName:"L0732"
        },
        attendees: [
            {
                emailAddress: {
                    address:"s17113209@stu.edu.tw",
                    name: "學生"
                },
                type: "required"
            }
        ]
    };

    let res = await client.api('/me/events')
        .post(event);
}

async function API_test() {
    try{
        let res = await client.api('/me/calendar')
            .version('beta')
            .get();
        console.log(res);
        jsonPre.innerHTML = JSON.stringify(res, null, 2);
    }catch (e) {
        // throw e
    }
}
////////////////////// Make requests to the graph //////////////////////

function signIn() {
    msalApplication.loginPopup();

    var btn_signIn = $("#SignIn");
    btn_signIn.attr("onClick", "signOut()");
    btn_signIn.text("登出MS帳號");
}

function signOut() {
    msalApplication.logout();
}

function openWindow_MS_API() {
    $("#MS_API_window").modal("show");
}

function change_MS_API(){ //選擇MS_API事件
    var selected_API = document.getElementById("MS_API").value;

    var MS_API_content = $("#MS_API_content");

    if(selected_API == "API_outlookCreateOnlineMeeting()"){
        var innerHTML =
            "<span id='attendees' class='mt-2'>" +
                "<button type='button' class='btn btn-outline-dark mt-2' onclick='add_attendees()'>新增參加者</button>" +
                "<p class='mt-2'><b>參加者:</b></p>" +
                "<input type='text' class='form-control mt-2 form-data'>" +
            "</span>";
        MS_API_content.html("");
    }else{
        MS_API_content.html("");
    }

    var btn_CallMSAPI = $("#call_MS_API");
    btn_CallMSAPI.attr("onclick", selected_API);
}

function add_attendees(){
    var attendees = $("#attendees");
    var content = "<input type='text' class='form-control mt-2 form-data'>";
    attendees.append(content);
}


$(document).ready(function () {
    if(msalApplication.getAccount() && !msalApplication.isCallback(window.location.hash)){ //檢查是否登入
        var btn_signIn = $("#SignIn");
        btn_signIn.attr("onClick", "signOut()");
        btn_signIn.text("登出MS帳號");
    }
});

