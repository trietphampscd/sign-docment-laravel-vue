import { post, put, remove, get } from '../utils/http'
import { AUTH_DESTROY } from '../store/actions.type'

export const authentication = {
  methods: {
    signup: function(data) {
      return post('auth/signup', data)
    },
    requestActivationToken: function(data) {
      return post('auth/signup/token', data)
    },
    activate: function(data) {
      return get('auth/signup/activate/' + data.token)
    },
    login: function(data) {
      return post('auth/login', data)
    },
    user: function() {
      return get('auth/user')
    },
    logout: function() {
      this.$store.dispatch(AUTH_DESTROY)
      this.$router.go()
    },
    createPasswordToken(data) {
      return post('password/create', data)
    },
    findPasswordToken(data) {
      return get('password/find/' + data.token)
    },
    resetPassword(data) {
      return post('password/reset', data)
    },
  }
}
