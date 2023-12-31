import { computed, reactive, watchEffect } from "vue";
import {randomString} from "../utils";
import { usePage } from "@inertiajs/vue3";

export type DefaultNotificationType = 'positive' | 'negative' | 'neutral'
export interface DefaultNotification<Type = DefaultNotificationType> {
  type: Type
  title: string
  content?: string | null
}

export interface INotification<Value = DefaultNotification> {
  id: string
  timestamp: number
  autoDismiss: boolean
  value: Value
}

const notifications = reactive<Record<string, Array<INotification<any>>>>({})
const displayed = reactive<Array<string>>([])

export type WatchListener<T = any> = (notification: INotification<T>, dismiss: () => void) => void

const watchListeners: Array<WatchListener> = []

export function watchForNotifications<T>(listener: WatchListener<T>) {
  watchListeners.push(listener)
}

export function watchBackendNotifications<T = any>(key: string = 'notifications') {
  type R = Record<string, Array<INotification<T>>>

  const notifications = computed(() => (usePage().props[key] || {}) as R)

  watchEffect(() => {
    Object.keys(notifications.value).forEach(stack => {
      notifications.value[stack].forEach(notification => {
        pushNotification(stack, notification)
      })
    })
  })
}

const removeFromStack = (stack: string, id: string) => {
  const idx = notifications[stack].findIndex(it => it.id === id)
  if (idx >= 0) {
    notifications[stack].splice(idx, 1)
  }
}

const dismissFromStack = (stack: string, notification: INotification) => {
  removeFromStack(stack, notification.id)
}

function pushNotification<Value = DefaultNotification>(stack: string, notification: INotification<Value>) {
  if (! notifications.hasOwnProperty(stack)) {
    notifications[stack] = []
  }

  const idx = displayed.findIndex(it => it === notification.id)
  if (idx >= 0) {
    return
  }

  displayed.push(notification.id)
  notifications[stack].unshift(notification)

  watchListeners.forEach(it => it(notification as any, () => dismissFromStack(stack, notification.id as any)))
}

export function useNotifications<Value = DefaultNotification>(stack: string = 'default') {
  if (! notifications.hasOwnProperty(stack)) {
    notifications[stack] = []
  }

  const push: (value: Value, autoDismiss: boolean) => void = (value, autoDismiss = true) => {
    const notification: INotification<Value> = {
      id: randomString(16),
      value,
      timestamp: Date.now(),
      autoDismiss: autoDismiss,
    }

    notify(notification)
  }

  const remove = (id: string) => {
    const idx = notifications[stack].findIndex(it => it.id === id)
    if (idx >= 0) {
      notifications[stack].splice(idx, 1)
    }
  }

  const dismiss = (notification: INotification<Value>) => {
    remove(notification.id)
  }

  const clear = () => {
    notifications[stack]
      .map(it => it.id)
      .forEach(it => remove(it))
  }

  const notify = (notification: INotification<Value>) => {
    const idx = displayed.findIndex(it => it === notification.id)
    if (idx >= 0) {
      return
    }

    displayed.push(notification.id)
    notifications[stack].unshift(notification)
  }

  return {
    notify, dismiss, clear, push, notifications: computed<Array<INotification<Value>>>(() => notifications[stack]),
  }
}
