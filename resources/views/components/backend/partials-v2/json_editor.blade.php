@php
$oldData = old($component);
$inputValue = ( !empty($oldData) )? $oldData : $value;
$required = $field['required'] ?? null;
// Clean alias name to be a safe JS selector/identifier
$editorId = "editor_" . str_replace(['[', ']', '.'], '_', $component) . "_" . ($index ?? '0');
@endphp
<div class="form-group mb-4">
    <label class="col-form-label font-weight-bold">{{ __($field['label']) }}</label>
    
    <!-- Ace Editor container -->
    <div id="{{ $editorId }}" class="border rounded" style="height: 300px; font-size: 14px; font-family: monospace;"></div>
    
    <!-- Hidden textarea that actually submits the form value -->
    <textarea
        @if(isset($index))
            x-model="field.{{ $field['name'] }}"
            x-bind:name="'{{ $component }}[' + index + '][{{ $field['name'] }}]'"
            x-bind:id="'hidden_{{ $editorId }}'"
        @else
            id="hidden_{{ $editorId }}"
        @endif
        name="{{ $component }}"
        class="d-none"
        @if($required) required @endif
    >{{ $inputValue }}</textarea>

    <!-- Formatting and status indicators -->
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div>
            <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="format_{{ $editorId }}">
                🪄 Format JSON
            </button>
        </div>
        <div id="error_{{ $editorId }}" class="text-danger fs-7" style="display: none;"></div>
    </div>
</div>

@once
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
@endonce

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var container = document.getElementById("{{ $editorId }}");
        var hiddenInput = document.getElementById("hidden_{{ $editorId }}");
        var errorDiv = document.getElementById("error_{{ $editorId }}");
        var formatBtn = document.getElementById("format_{{ $editorId }}");

        var editor = ace.edit(container);
        editor.setTheme("ace/theme/chrome");
        editor.session.setMode("ace/mode/json");
        editor.session.setUseWorker(false); // Disable web workers to prevent cross-origin issues
        editor.setShowPrintMargin(false);
        editor.setOptions({
            tabSize: 2,
            useSoftTabs: true
        });

        // Set initial value
        editor.setValue(hiddenInput.value || "{\n  \"@context\": \"https://schema.org\",\n  \"@type\": \"\"\n}");
        editor.clearSelection();

        // Listen for editor updates to validate and write back to target input
        editor.session.on('change', function() {
            var value = editor.getValue();
            hiddenInput.value = value;

            // Trigger input event manually for Alpine x-model bindings if applicable
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));

            try {
                if (value.trim()) {
                    JSON.parse(value);
                }
                errorDiv.style.display = "none";
                container.style.borderColor = "#e4e6ef";
            } catch (e) {
                errorDiv.innerText = "⚠️ Invalid JSON: " + e.message;
                errorDiv.style.display = "block";
                container.style.borderColor = "#f1416c";
            }
        });

        // Format/Beautify button functionality
        formatBtn.addEventListener("click", function() {
            try {
                var content = editor.getValue();
                if (content.trim()) {
                    var parsed = JSON.parse(content);
                    editor.setValue(JSON.stringify(parsed, null, 2));
                    editor.clearSelection();
                }
            } catch (e) {
                alert("Cannot format: JSON is invalid.\n" + e.message);
            }
        });
    });
</script>
