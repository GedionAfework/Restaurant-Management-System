import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function usePermissions() {
  const page = usePage()
  
  const user = computed(() => {
    return page.props.auth?.user || null
  })

  const permissions = computed(() => {
    return user.value?.permissions || []
  })

  const hasPermission = (permissionSlug: string): boolean => {
    if (!user.value) return false
    if (user.value.is_admin) return true // Admins have all permissions
    return permissions.value.includes(permissionSlug)
  }

  const hasAnyPermission = (permissionSlugs: string[]): boolean => {
    if (!user.value) return false
    if (user.value.is_admin) return true
    return permissionSlugs.some(slug => permissions.value.includes(slug))
  }

  const hasAllPermissions = (permissionSlugs: string[]): boolean => {
    if (!user.value) return false
    if (user.value.is_admin) return true
    return permissionSlugs.every(slug => permissions.value.includes(slug))
  }

  const hasRole = (roleSlug: string): boolean => {
    if (!user.value) return false
    return user.value.role?.slug === roleSlug
  }

  return {
    user,
    permissions,
    hasPermission,
    hasAnyPermission,
    hasAllPermissions,
    hasRole,
  }
}

