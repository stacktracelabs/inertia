import {readonly, ref, watch} from "vue"
import type { Ref } from "vue"

export interface Toggle {
  active: Readonly<Ref<boolean>>
  activate: () => void
  deactivate: () => void
}

export function useToggle(initiallyActive: boolean = false): Toggle {
  const active = ref(initiallyActive)

  const activate = () => {
    active.value = true
  }

  const deactivate = () => {
    active.value = false
  }

  return {
    active: readonly(active),
    activate, deactivate
  }
}

export function onActivated(toggle: Toggle, callback: () => void) {
  watch(toggle.active, isActive => {
    if (isActive) {
      callback()
    }
  })
}

export function onDeactivated(toggle: Toggle, callback: () => void) {
  watch(toggle.active, isActive => {
    if (! isActive) {
      callback()
    }
  })
}
