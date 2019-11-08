import config from '../config/config'
import axios from 'axios'
import JwtService from '../mixins/jwt.service'

// const defaultToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImY3MjQ1MzFkOGRjYjU4NWJlNDJmOWM3MTVlZjhkYmUxODE2ZTUzMjdmMTM3OGRmYmIzZWIxZWEzNzA2ZTdlOGExZjg0MGViNjY5ODkyYmE3In0.eyJhdWQiOiIxIiwianRpIjoiZjcyNDUzMWQ4ZGNiNTg1YmU0MmY5YzcxNWVmOGRiZTE4MTZlNTMyN2YxMzc4ZGZiYjNlYjFlYTM3MDZlN2U4YTFmODQwZWI2Njk4OTJiYTciLCJpYXQiOjE1NjgxODMyNDksIm5iZiI6MTU2ODE4MzI0OSwiZXhwIjoxNTk5ODA1NjQ4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.i9FduoFbZdQl_B_ClKuAAIkskdIPPyYVQeHKCD3lKGlIwn1qt_478BcURvkvpaEowsiqAJnJMR7Wv894WSYaJkQb21-uykV-s_e5M-GXxVE_BbZHISzjrPLMOarNM_uArwlIhvd9ozuHD1Vh5O1pf0PtocfARwK8p02JmLAiXO6UuyJJwhQrw4hwTbHEfhv1SchLnvDclLNCByS_o9hpdtq9p3WUxT6zC_-Lp93qo1rAjlSb3Ngud3sJAPXgpk4MYQ1ghWjrk-dKNMvSbMn5kb4shctFYRIGHUxJkRgsZEOB4hzs-nVU2CA3R5LQmuBW9gIccUoZwOC1y6NLY5xsaVd6D3RNoB5YcTlsiYxg0kqAoU25hawQtV4BUe22rcEdMxhYL8wclyrnhmAZkLe374uwLFf9-88ZzcJ0PwhATDeu5KCjJ4hdH7N6OoFGBBI7ZzyKVoJ9swoYjlEJI4lSxGGRoPv9d3QV0ykF27tu0Sacipb3knafvYLNFzgZCo9iacRIXx2W-dvxV95up755yRy6xov7DNvXDLUnPrmZgDBlgATBUKVW8Q-_onmlJYQNq_VI-8i2yxmEud8XZDw9IVggVgndbzfmpF7qhwAScIthre-sRr7eGv7M9UvpTFPtXEOyCQyywhEAHBVvHTouvsQUaRksb9VXs4EO_v_3PEk";
// const token = window.localStorage.getItem('token') || null;

const getHeaders = () => {
    // Handle authorization token
    return {
      'Accept': 'application/json',
      'Authorization': 'Bearer ' + JwtService.getToken()
    }
}

export function post(uri, data) {
    return axios.post(endpoint(uri), data, {
        headers: getHeaders()
    })
}

export function put(uri, data) {
    return axios.put(endpoint(uri), data, {
        headers: getHeaders()
    })
}

export function remove(uri, data) {
    return axios.delete(endpoint(uri), {
        data,
        headers: getHeaders()
    })
}

export function get(uri) {
    return axios.get(endpoint(uri), {
        headers: getHeaders()
    })
}

export function getOutSide(uri, headers) {
    return axios.get(uri, {
        headers: getHeaders(),
        ...headers,
    })
}

export function endpoint(uri) {
    return config.SERVER_URL + '/api/' + uri
}
