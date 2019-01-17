using CapiValidation.Data.Interfaces;

namespace CapiValidation.Data.Entities
{
    public class EntityBase : IEntityBase { }

    public class EntityBase<T> : IEntityBase<T>
    {
        public T Id { get; set; }
    }
}