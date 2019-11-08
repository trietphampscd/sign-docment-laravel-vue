import { post, put, remove, get } from '../utils/http'

export const firstDocuSign = {
    methods: {
        getCompanySizes: function() {
            return get('company-sizes/all')
        },
        getIndustries: function() {
            return get('industries/all')
        },
        getDepartments: function() {
            return get('departments/all')
        },
        createClient: function(data)
        {
            return post('clients/new', data)
        }
    }
}
