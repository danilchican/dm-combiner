from abc import ABC, abstractmethod
import inspect


class Framework(ABC):

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

    @property
    def methods_params(self) -> dict:
        """
        Get the names of a methods parameters.
        """
        methods_params_dict = {}
        for method_name, method_instance in self.methods.items():
            methods_params_dict[method_name] = inspect.getargspec(method_instance)[0]
        return methods_params_dict

    def get_subclasses(self) -> dict:
        """
        Fetch all subclasses and return dict with instances if this subclasses.
        """
        subclasses = {}
        for subclass in self.__class__.__subclasses__():
            subclasses[subclass.__name__] = subclass
        return subclasses


if __name__ == '__main':
    lib = Framework()
    print(Framework().__class__.__subclasses__)
