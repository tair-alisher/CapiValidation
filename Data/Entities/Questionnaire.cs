using System;
using System.ComponentModel.DataAnnotations.Schema;
using CapiValidation.Data.Interfaces;

namespace CapiValidation.Data.Entities
{
    public class Questionnaire : EntityBase<string>
    {
        public string Title { get; set; }
        public Guid QuestionnaireId { get; set; }
        public DateTime CreationDate { get; set; }
        public bool IsDeleted { get; set ;}
        public bool Disabled { get; set; }
    }
}