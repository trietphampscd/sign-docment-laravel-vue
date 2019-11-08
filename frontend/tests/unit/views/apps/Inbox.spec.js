import Vue from 'vue'
import { shallowMount, mount } from '@vue/test-utils'
import BootstrapVue from 'bootstrap-vue'
import Inbox from '@/views/apps/email/Inbox'

Vue.use(BootstrapVue)

describe('Inbox.vue', () => {
  it('has a name', () => {
    expect(Inbox.name).toMatch('inbox')
  })
  it('is Vue instance', () => {
    const wrapper = shallowMount(Inbox)
    expect(wrapper.isVueInstance()).toBe(true)
  })
  it('is Inbox', () => {
    const wrapper = shallowMount(Inbox)
    expect(wrapper.is(Inbox)).toBe(true)
  })
  it('should render correct content', () => {
    const wrapper = mount(Inbox)
    expect(wrapper.find('div.email-app > nav > a.btn').text()).toMatch('New Email')
  })
  test('renders correctly', () => {
    const wrapper = shallowMount(Inbox)
    expect(wrapper.element).toMatchSnapshot()
  })
})
