using CapiValidation.Data.Entities;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;

namespace CapiValidation.Data.Configurations
{
    public class QuestionnaireConfig : IEntityTypeConfiguration<Questionnaire>
    {
        public void Configure(EntityTypeBuilder<Questionnaire> builder)
        {
            builder.ToTable("questionnairebrowseitems", schema: "plainstore");
            builder.Property(q => q.Id).HasColumnName("id");
            builder.Property(q => q.Title).HasColumnName("title");
            builder.Property(q => q.QuestionnaireId).HasColumnName("questionnaireid");
            builder.Property(q => q.CreationDate).HasColumnName("creationdate");
            builder.Property(q => q.IsDeleted).HasColumnName("isdeleted");
            builder.Property(q => q.Disabled).HasColumnName("disabled");
        }
    }
}