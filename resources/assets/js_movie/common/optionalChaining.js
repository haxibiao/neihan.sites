// vue2.x template暂时不支持optional chaining，因为实现一个类型作用的方法，添加到vue原型上
export function optionalChaining(obj, chaining) {
    if (obj === null || typeof obj !== 'object') return null;
    let result = { ...obj };
    const keys = chaining.split('.');
    for (const key of keys) {
        if (result[key] !== undefined && result[key] !== null) {
            result = result[key];
        } else {
            return undefined;
        }
    }
    return result;
}
