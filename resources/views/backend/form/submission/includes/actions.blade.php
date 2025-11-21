<x-utils.view-button :href="route($route.'.show', ['form' => $form, 'submission' => $model])" />
<x-utils.delete-button :href="route($route.'.destroy', ['form' => $form, 'submission' => $model])" />
