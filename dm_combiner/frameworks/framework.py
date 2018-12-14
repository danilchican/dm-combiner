import inspect
from abc import ABC


class Framework(ABC):
    """
    Base class for all frameworks.
    """

    def get_subclasses(self) -> dict:
        """
        Fetch all subclasses and return dict with instances if this subclasses.
        """
        subclasses = {}
        for subclass in self.__class__.__subclasses__():
            subclasses[subclass.__name__] = subclass
        return subclasses

    def get_method_params(self, method_name) -> list:
        """
        Get the names of a methods parameters.
        """
        restricted_args = ['return', 'data']
        args = []
        method_instance = self.methods.get(method_name)
        if method_instance:
            # return tuple with 7 elements, but we need only last
            method_args_info = inspect.getfullargspec(method_instance)[6]
            for arg, arg_type in method_args_info.items():
                if arg not in restricted_args:
                    args.append({'name': arg, 'type': arg_type.__name__})
            return args
        return None

    @property
    def methods(self) -> dict:
        """
        Fetch all subclasses and return dict with instances if this subclasses.
        """
        methods = {}
        for method_name in self.__class__.__dict__:
            method_obj = getattr(self.__class__, method_name)
            if callable(method_obj) and not method_name.startswith("__"):
                methods[method_name] = method_obj
        return methods


if __name__ == '__main':
    lib = Framework()
    print(Framework().__class__.__subclasses__)
