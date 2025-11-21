module.exports = [
    "strapi::errors",
    {
        resolve: "./src/middlewares/handlingError.js",
    },
    // 'strapi::security',
    {
        name: "strapi::security",
        config: {
            xssFilter: true,
            originAgentCluster: true,
            contentSecurityPolicy: {
                useDefaults: true,
                directives: {
                    "connect-src": ["'self'", "https:"],
                    "img-src": [
                        "'self'",
                        "data:",
                        "blob:",
                        "storage.googleapis.com",
                        "assets.banksaqu.co.id",
                        "*",
                    ],
                    "media-src": [
                        "'self'",
                        "data:",
                        "blob:",
                        "storage.googleapis.com",
                        "assets.banksaqu.co.id",
                        "*",
                    ],
                    "frame-src": ["'self'", "*", "assets.banksaqu.co.id"],
                    upgradeInsecureRequests: null,
                },
            },
        },
    },
    // 'strapi::cors',
    {
        name: "strapi::cors",
        config: {
            enabled: true,
            credentials: false,
            headers: "*",
            // origin: ['https://web-weilite-we19.antikode.dev','https://api-weilite-we19.antikode.dev']
            origin: ["*"],
        },
    },
    {
        name: "strapi::session",
        config: { enabled: true },
    },

    // 'strapi::poweredBy',
    "strapi::logger",
    "strapi::query",
    {
        name: "strapi::body",
        config: {
            formidable: {
                maxFileSize: 200 * 1024,
            },
        },
    },
    "strapi::session",
    "strapi::favicon",
    "strapi::public",
    {
        resolve: "./src/middlewares/addHeader.js",
    },
    {
        resolve: "./src/middlewares/checkToken.js",
    },
    {
        resolve: "./src/middlewares/blacklist.js",
    },
    {
        resolve: "./src/middlewares/idleTime.js",
    },
];
