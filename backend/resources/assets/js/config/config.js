export default {
  //BASE_URL: 'https://api.coffeesign.io',
  //CLIENT_URL: 'https://member.coffeesign.io',
  BASE_URL: 'http://localhost:8000',
  // CLIENT_URL: 'https://localhost:8080',
  // API_URL: this.BASE_URL + '/api/'

  GOOGLE_CONFIG: {
    developerKey: "AIzaSyD4DrbIkBl8vTjtabmS55miKjzIna6cw4w",
    clientId: "476458043340-jk59tadh83tp9m4pmhrlgi9lil0tvhte.apps.googleusercontent.com",
    scope: "https://www.googleapis.com/auth/drive.readonly",
  },
  DROPBOX_KEY: "cbsxt1jdrtiju4w",
  MICROSOFT: {
    CLIENT_ID: "d1fe3a34-6acb-4ce7-a97a-27d66e574892",
    endpointHint: "api.onedrive.com",
    // redirectUri: "https://www.coffeesign.io:3000/callback-onedrive",
    redirectUri: "http://localhost:8000/callback-onedrive",
  },
  
  BOX: {
    CLIENT_ID: "c86lo3q4tkohz7sw8hh520mdhhx4ljd4"
  },
  KAKAO: {
    javascriptKey: "c089c8172def97eb00c07217cae17495"
  }
}