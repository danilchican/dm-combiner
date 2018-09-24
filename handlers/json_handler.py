from utils.decorators import log_validators


class JsonHandler():
    commands = {
        'k-means': ['n_clusters', 'max_iter', 'init', 'random_state'],
        'load': ['path', 'data_type', 'columns'],
        'save': ['data_type'],
        'normalize': ['norm', 'axis', 'copy', 'return_norm'],
        'scale': ['axis', 'with_mean', 'with_std', 'copy'],
        'pca': ['n_components'],
    }

    def __init__(self, json):
        self.json = json

    @log_validators
    def _validate_commands(self):
        is_commands_validating_success = True
        error = ''
        for al_step in self.json.values():
            if al_step.get('name') not in self.commands.keys():
                is_commands_validating_success = False
                error = 'No such command: {command}'.format(command=al_step['name'])
        return is_commands_validating_success, error

    @log_validators
    def _validate_params(self):
        is_params_validating_success = True
        error = ''
        for al_step in self.json.values():
            for param in al_step.get('params'):
                if param not in self.commands.get(al_step.get('name')):
                    is_params_validating_success = False
                    error = "No such param in '{command}' command: {param}".format(command=al_step['name'], param=param)
        return is_params_validating_success, error

    @log_validators
    def _validate_json_structure(self):
        is_validating_success = True
        error = ''
        for al_step in self.json.values():
            if not(isinstance(al_step, dict) and al_step.get('name') and isinstance(al_step.get('params'), dict)):
                is_validating_success = False
                error = 'Not valid json structure'
        return is_validating_success, error

    def validate_json(self):
        validation_functions = [self._validate_json_structure, self._validate_commands, self._validate_params]

        for validation_function in validation_functions:
            is_validation_success, error = validation_function()
            if not is_validation_success:
                return is_validation_success, error

        return is_validation_success, error