using System;
using System.Collections.Generic;
using System.Linq.Expressions;
using CapiValidation.Data.Interfaces;

namespace CapiValidation.Data
{
    public class Specification<T> : ISpecification<T> where T : class, IEntityBase
    {
        public Expression<Func<T, bool>> Criteria { get; }

        public Specification(Expression<Func<T, bool>> criteria)
            => Criteria = criteria;

        public List<Expression<Func<T, object>>> Includes { get; } = new List<Expression<Func<T, object>>>();

        public List<string> IncludeStrings { get; } = new List<string>();

        protected virtual void AddInclude(Expression<Func<T, object>> includeExpression)
            => Includes.Add(includeExpression);

        protected virtual void AddInclude(string includeString)
            => IncludeStrings.Add(includeString);
    }
}