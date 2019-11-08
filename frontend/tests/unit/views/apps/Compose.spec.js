import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import Compose from '@/views/apps/email/Compose'

Vue.use(BootstrapVue)

describe('Compose.vue', () => {
  it('has a name', () => {
    expect(Compose.name).toMatch('compose')
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(Compose)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is Compose', () => {
    const wrapper = shallowMount(Compose)
    expect(wrapper.is(Compose)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(Compose)
    expect(wrapper.find('main > p.text-center').text()).toMatch('New Message')
  })
  test('renders correctly', () => {
    const wrapper = shallowMount(Compose)
    expect(wrapper.element).toMatchSnapshot()
  })
})

