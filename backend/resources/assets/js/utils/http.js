import config from '../config/config'
import axios from 'axios'

const defaultToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijk2NTdhZGZlMjU0MmUxM2Y3YmZjMjNlNGQ1ZDBmYzJmZjEwOGIzOGZjODRiYThmOTY4NmVkY2Q3MjMxOWRmZTgwZDI3NGFlNGY4MWI2YmMwIn0.eyJhdWQiOiIxIiwianRpIjoiOTY1N2FkZmUyNTQyZTEzZjdiZmMyM2U0ZDVkMGZjMmZmMTA4YjM4ZmM4NGJhOGY5Njg2ZWRjZDcyMzE5ZGZlODBkMjc0YWU0ZjgxYjZiYzAiLCJpYXQiOjE1NzA0NDQwODksIm5iZiI6MTU3MDQ0NDA4OSwiZXhwIjoxNjAyMDY2NDg5LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.AZwwNgFCkh2mbeCNY9yhcyVGOf-OnEyChp-mW0e8E1dXQNnF6wYUZIGj2RdwtAgBy2mJVH8vxXpdtTrxU13IyMDMoc_modAso8hGD0BkAp_gVhMLoHEH4_TDUZmxJlbDqMEtrYqnRgHdoaJ-oXY8HurF9XkbpL74k1IQMktH63-LdkF3HxDsqY73GGo8_ivaCWvjP9F5eQlW-FoDtJJzSAQVrZ4x0rbJSGTt8XRuPLqBFkrcM1DJlTbi9PPtGRSB3dYg6ezp9AueISPbLpxCKVePA4rqbWm_Knfc-146eERilA3u5aTbiI1n29Kl5m4VGfX3aCMhErKR3YIsPfacjwR12MW8jjtUWbb3oWZTmV1gL5INvh4lYdyHNQGP5dpzGsewtrEbfY_Dt2HhojlSxeLlvTxdDFwCaptHnQ6Skr3EJShxO60wfWCSI-S_I_0XamI-gZVrj3M8PFyhKkUIjfYiufLCe5J-6tM1SCCOCob2xUDVOgcYST6UbSjnk4McYlyPumsU3JJlHluTECb6U-JodKNI4j7kTzi5_CqAnPyDfdndX4nRVUli82UbYRx-vO6bYC8ON7D20cZyqm-A8CRTrZZXBeKzDzdlfvlNfKnArB6Se40m9r6Na32Wf9_IBtv1R5VpBqnrA6HOYix26QbmVqGSrtD2JJH6enjoP5g";
const token = window.localStorage.getItem('token') || defaultToken;

const getHeaders = () => {
    // Handle authorization token
    return {
        'Authorization': 'Bearer ' + token
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
    return config.BASE_URL + '/api/' + uri
}
