using System;
using System.Collections.Generic;
using System.Linq.Expressions;

namespace CapiValidation.Data.Interfaces
{
    public interface ISpecification<T> where T : class, IEntityBase
    {
        Expression<Func<T, bool>> Criteria { get; }
        List<Expression<Func<T, object>>> Includes { get; }
        List<string> IncludeStrings { get; }
    }
}