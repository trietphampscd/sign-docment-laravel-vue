import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import Message from '@/views/apps/email/Message'

Vue.use(BootstrapVue)

describe('Message.vue', () => {
  it('has a name', () => {
    expect(Message.name).toMatch('message')
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(Message)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is Message', () => {
    const wrapper = shallowMount(Message)
    expect(wrapper.is(Message)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(Message)
    expect(wrapper.find('div.email-app > nav > a.btn').text()).toMatch('New Email')
  })
  test('renders correctly', () => {
    const wrapper = shallowMount(Message)
    expect(wrapper.element).toMatchSnapshot()
  })
})
