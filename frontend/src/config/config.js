export default {
  /** URL Config */
  BASE_URL: process.env.VUE_APP_URL,
  SERVER_URL: process.env.VUE_APP_SERVER_URL,
  STORAGE_URL: process.env.VUE_APP_STORAGE_URL,

  /* Social Config */
  GOOGLE_CONFIG: {
    developerKey: process.env.VUE_APP_GOOGLE_DRIVE_DEVELOPER_KEY,
    clientId: process.env.VUE_APP_GOOGLE_DRIVE_CLIENT_ID,
    scope: process.env.VUE_APP_GOOGLE_DRIVE_SCOPE,
  },
  DROPBOX_KEY: process.env.VUE_APP_DROPBOX_KEY,
  MICROSOFT: {
    CLIENT_ID: process.env.VUE_APP_MICROSOFT_CLIENT_ID,
    endpointHint: process.env.VUE_APP_MICROSOFT_ENDPOINT,
    redirectUri: process.env.VUE_APP_MICROSOFT_REDIRECTURI,
  },
  BOX: {
    CLIENT_ID: process.env.VUE_APP_BOX_CLIENT_ID
  },
  KAKAO: {
    javascriptKey: process.env.VUE_APP_KAKAO_KEY
  }
}
