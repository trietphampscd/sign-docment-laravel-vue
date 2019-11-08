import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import ValidationForms from '@/views/forms/ValidationForms'


Vue.use(BootstrapVue)

describe('ValidationForms.vue', () => {
  it('has a name', () => {
    expect(ValidationForms.name).toMatch('ValidationForms')
  })
  it('has a created hook', () => {
    expect(typeof ValidationForms.data).toMatch('function')
  })
  it('sets the correct default data', () => {
    expect(typeof ValidationForms.data).toMatch('function')
    const defaultData = ValidationForms.data()
    expect(defaultData.submitted).toBe(false)
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(ValidationForms)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is ValidationForms', () => {
    const wrapper = shallowMount(ValidationForms)
    expect(wrapper.is(ValidationForms)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(ValidationForms)
    expect(wrapper.find('div.card-header').text()).toMatch('Form Validation')
  })
  test('renders correctly', () => {
    const wrapper = shallowMount(ValidationForms)
    expect(wrapper.element).toMatchSnapshot()
  })
})
