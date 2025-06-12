import { forwardRef } from "react";
import { clsx } from "clsx";

const Input = forwardRef(
  ({ className, type = "text", value, onChange, error, label, id, ...props }, ref) => {
    const inputId = id || label?.toLowerCase().replace(/\s+/g, "-") || "input";

    return (
      <div className="space-y-1">
        {label && (
          <label htmlFor={inputId} className="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {label}
          </label>
        )}
        <input
          id={inputId}
          type={type}
          className={clsx(
            "block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400",
            "focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500",
            "dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400",
            error && "border-red-500 focus:ring-red-500 focus:border-red-500",
            className
          )}
          ref={ref}
          onChange={onChange}
          {...(value !== undefined && { value })}
          {...props}
        />
        {error && <p className="text-sm text-red-600 dark:text-red-400">{error}</p>}
      </div>
    );
  }
);

Input.displayName = "Input";

export default Input;
